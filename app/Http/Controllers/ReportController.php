<?php

namespace App\Http\Controllers;

use App\Models\{Customer, Lead, FollowUp, Activity, User};
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $staffId = $request->query('staff_id');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        if (in_array($user->role, ['admin', 'manager'])) {
            $users = User::whereIn('role', ['manager', 'sales_staff'])
                        ->get()
                        ->groupBy('role');
        } else {
            $staffId = $user->id;
            $users = collect([$user]);
        }

        $applyFilters = function($query, $userColumn = 'assigned_user_id') use ($staffId, $startDate, $endDate) {
            return $query->when($staffId, fn($q) => $q->where($userColumn, $staffId))
                         ->when($startDate, fn($q) => $q->whereDate('created_at', '>=', $startDate))
                         ->when($endDate, fn($q) => $q->whereDate('created_at', '<=', $endDate));
        };

        $pipeline = Lead::selectRaw('status, count(*) as count')
            ->when($staffId, fn($q) => $q->where('assigned_user_id', $staffId))
            ->when($startDate, fn($q) => $q->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn($q) => $q->whereDate('created_at', '<=', $endDate))
            ->groupBy('status')
            ->pluck('count', 'status');

        $data = [
            'totalCustomers' => $applyFilters(Customer::query())->count(),
            'totalLeads'     => $applyFilters(Lead::query())->count(),
            'completedFollowUps' => $applyFilters(FollowUp::where('status', 'completed'), 'user_id')->count(),
            'pendingFollowUps'   => $applyFilters(FollowUp::where('status', 'pending'), 'user_id')->count(),
            'pipeline' => $pipeline,
            
            'activities' => Activity::with(['user', 'lead', 'customer'])
                ->when($staffId, fn($q) => $q->where('user_id', $staffId))
                ->when($startDate, fn($q) => $q->whereDate('created_at', '>=', $startDate))
                ->when($endDate, fn($q) => $q->whereDate('created_at', '<=', $endDate))
                ->latest()
                ->limit(15)
                ->get(),

            'completedFollowUpDetails' => FollowUp::with(['user', 'lead'])
                ->where('status', 'completed')
                ->when($staffId, fn($q) => $q->where('user_id', $staffId))
                ->when($startDate, fn($q) => $q->whereDate('updated_at', '>=', $startDate))
                ->when($endDate, fn($q) => $q->whereDate('updated_at', '<=', $endDate))
                ->latest()
                ->limit(10)
                ->get()
        ];

        if ($request->has('export')) {
            $pdf = Pdf::loadView('reports.pdf', compact('data', 'startDate', 'endDate', 'staffId'));
            return $request->has('preview') ? $pdf->stream('report.pdf') : $pdf->download('report.pdf');
        }

        if ($request->has('export_csv')) {
            return $this->exportCsv($data, $staffId, $startDate, $endDate);
        }

        return view('reports.index', compact('data', 'users', 'staffId', 'startDate', 'endDate'));
    }

    private function exportCsv($data, $staffId, $startDate, $endDate)
    {
        $staffName = $staffId ? User::find($staffId)->name : 'All Staff';
        
        $callback = function() use ($data, $staffName, $startDate, $endDate) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, ['CRM PERFORMANCE REPORT']);
            fputcsv($file, ['Staff:', '="' . $staffName . '"']);
            fputcsv($file, ['Range:', '="' . ($startDate ?? 'Beginning') . ' to ' . ($endDate ?? 'Today') . '"']);
            fputcsv($file, ['Generated:', '="' . date('Y-m-d H:i') . '"']);
            fputcsv($file, []);

            fputcsv($file, ['SUMMARY METRICS']);
            fputcsv($file, ['Metric', 'Count']);
            fputcsv($file, ['Total Customers', '="' . $data['totalCustomers'] . '"']);
            fputcsv($file, ['Total Leads', '="' . $data['totalLeads'] . '"']);
            fputcsv($file, ['Completed Follow-ups', '="' . $data['completedFollowUps'] . '"']);
            fputcsv($file, ['Pending Tasks', '="' . $data['pendingFollowUps'] . '"']);
            fputcsv($file, []);

            fputcsv($file, ['LEAD PIPELINE DISTRIBUTION']);
            foreach ($data['pipeline'] as $status => $count) { 
                fputcsv($file, [$status, '="' . $count . '"']); 
            }
            fputcsv($file, []);

            fputcsv($file, ['DETAILED ACTIVITY LOGS']);
            fputcsv($file, ['Staff', 'Type', 'Description', 'Link', 'Date']);
            foreach ($data['activities'] as $a) {
                fputcsv($file, [
                    '="' . ($a->user->name ?? 'System') . '"',
                    '="' . $a->activity_type . '"',
                    '="' . $a->description . '"',
                    '="' . ($a->lead ? 'Lead: '.$a->lead->name : ($a->customer ? 'Customer: '.$a->customer->first_name : 'N/A')) . '"',
                    '="' . $a->created_at->format('Y-m-d H:i') . '"'
                ]);
            }
            fputcsv($file, []);

            fputcsv($file, ['COMPLETED FOLLOW-UPS']);
            fputcsv($file, ['Staff', 'Task Title', 'Description', 'Outcome', 'Completed At']);
            foreach ($data['completedFollowUpDetails'] as $f) {
                fputcsv($file, [
                    '="' . ($f->user->name ?? 'N/A') . '"',
                    '="' . $f->title . '"',
                    '="' . $f->description . '"',
                    '="' . (($f->updated_at <= $f->due_date) ? 'On-Time' : 'Overdue') . '"',
                    '="' . $f->updated_at->format('Y-m-d H:i') . '"'
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=NullCRM_Report_" . date('Y-m-d') . ".csv"
        ]);
    }
}