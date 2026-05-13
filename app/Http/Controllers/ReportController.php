<?php

namespace App\Http\Controllers;

use App\Models\{Customer, Lead, FollowUp, Activity, User};
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; // Ensure you have barryvdh/laravel-dompdf installed

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $staffId = $request->query('staff_id');
        $users = User::all();
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $dateScope = function($query) use ($startDate, $endDate) {
        $query->when($startDate, fn($q) => $q->whereDate('created_at', '>=', $startDate))
              ->when($endDate, fn($q) => $q->whereDate('created_at', '<=', $endDate));
        };

        $customerQuery = Customer::query();
        $leadQuery = Lead::query();
        $followUpQuery = FollowUp::query();
        $activityQuery = Activity::with(['user', 'lead', 'customer']);

        $dateScope($activityQuery);
        $dateScope($followUpQuery);

        $data = [
            'totalCustomers' => $customerQuery->count(),
            'totalLeads' => $leadQuery->count(),
            'completedFollowUps' => $followUpQuery->where('status', 'completed')->count(),
            'pendingFollowUps' => $followUpQuery->where('status', 'pending')->count(),
            'pipeline' => Lead::selectRaw('status, count(*) as count')
                               ->when($staffId, fn($q) => $q->where('assigned_user_id', $staffId))
                               ->groupBy('status')->pluck('count', 'status'),
            'activities' => Activity::with(['user', 'lead', 'customer'])
                        ->latest()
                        ->limit(15)
                        ->get(['user_id', 'lead_id', 'customer_id', 'description', 'activity_type', 'created_at']),
            'completedFollowUpDetails' => FollowUp::query()
                        ->where('status', 'completed') // Ensure this string matches exactly what you use to mark them done
                        ->when($staffId, function($query) use ($staffId) {
                            $query->where('user_id', $staffId);
                        })
                        ->with(['user', 'lead'])
                        ->latest()
                        ->limit(10)
                        ->get()
                            ];

        if ($request->has('export')) {
            $pdf = Pdf::loadView('reports.pdf', compact('data'));
            
            if ($request->has('preview')) {
                return $pdf->stream('report-preview.pdf');
            }
            
            return $pdf->download('report.pdf');
        }

        if ($request->has('export_csv')) {
                $startDate = $request->query('start_date');
                $endDate = $request->query('end_date');
                $staffId = $request->query('staff_id');
                $staffName = $staffId ? User::find($staffId)->name : 'All Staff';

                $activities = Activity::with(['user', 'lead', 'customer'])
                    ->when($staffId, fn($q) => $q->where('user_id', $staffId))
                    ->when($startDate, fn($q) => $q->whereDate('created_at', '>=', $startDate))
                    ->when($endDate, fn($q) => $q->whereDate('created_at', '<=', $endDate))
                    ->latest()->get();

                $followUps = FollowUp::where('status', 'completed')
                    ->when($staffId, fn($q) => $q->where('user_id', $staffId))
                    ->when($startDate, fn($q) => $q->whereDate('updated_at', '>=', $startDate))
                    ->when($endDate, fn($q) => $q->whereDate('updated_at', '<=', $endDate))
                    ->with(['user', 'lead'])->latest()->get();

                $callback = function() use ($activities, $followUps, $data, $staffName, $startDate, $endDate) {
                    $file = fopen('php://output', 'w');

                    // Report Header
                    fputcsv($file, ['CRM ACTIVITY REPORT']);
                    fputcsv($file, ['Staff:', $staffName]);
                    fputcsv($file, ['Date Range:', ($startDate ?? 'All') . ' to ' . ($endDate ?? 'All')]);
                    fputcsv($file, []);

                    // Counters
                    fputcsv($file, ['--- SUMMARY COUNTERS ---']);
                    fputcsv($file, ['Metric', 'Count']);
                    fputcsv($file, ['Total Customers', $data['totalCustomers']]);
                    fputcsv($file, ['Total Leads', $data['totalLeads']]);
                    fputcsv($file, ['Completed Follow-ups', $data['completedFollowUps']]);
                    fputcsv($file, ['Pending Follow-ups', $data['pendingFollowUps']]);
                    fputcsv($file, []);

                    // Pipeline
                    fputcsv($file, ['--- LEAD PIPELINE ---']);
                    foreach ($data['pipeline'] as $status => $count) {
                        fputcsv($file, [ucfirst($status), $count]);
                    }
                    fputcsv($file, []);

                    // Activities
                    fputcsv($file, ['--- ACTIVITIES ---']);
                    fputcsv($file, ['Staff', 'Type', 'Description', 'Date']);
                    foreach ($activities as $a) {
                        fputcsv($file, [
                            $a->user->name ?? 'N/A', 
                            $a->activity_type, 
                            $a->description, 
                            "\t" . $a->created_at->format('Y-m-d') // Tab prevents # format
                        ]);
                    }
                    fputcsv($file, []);

                    // Completed Follow-ups
                    fputcsv($file, ['--- RECENTLY COMPLETED FOLLOW-UPS ---']);
                    fputcsv($file, ['Staff', 'Title', 'Description', 'Completed Date']);
                    foreach ($followUps as $f) {
                        fputcsv($file, [
                            $f->user->name ?? 'N/A', 
                            $f->title, 
                            $f->description, 
                            "\t" . $f->updated_at->format('Y-m-d') // Tab prevents # format
                        ]);
                    }
                    fclose($file);
                };

                return response()->stream($callback, 200, [
                    "Content-Type" => "text/csv",
                    "Content-Disposition" => "attachment; filename=Report_" . date('Y-m-d') . ".csv"
                ]);
            }
        return view('reports.index', compact('data', 'users', 'staffId', 'startDate', 'endDate'));
    }

    
}