<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->query('search');
        $userIdFilter = $request->query('user_id');

        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $leadQuery = Activity::whereNotNull('lead_id')->with(['lead', 'user'])->latest();
        $customerQuery = Activity::whereNotNull('customer_id')->with(['customer', 'user'])->latest();

        if ($user->role === 'sales_staff') {
            $leadQuery->where('user_id', $user->id);
            $customerQuery->where('user_id', $user->id);
        } elseif (in_array($user->role, ['admin', 'manager']) && $userIdFilter) {
            $leadQuery->where('user_id', $userIdFilter);
            $customerQuery->where('user_id', $userIdFilter);
        }

        if ($search) {
            $leadQuery->whereHas('lead', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
            $customerQuery->whereHas('customer', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        if ($startDate) {
            $leadQuery->whereDate('activity_date', '>=', $startDate);
            $customerQuery->whereDate('activity_date', '>=', $startDate);
        }
        if ($endDate) {
            $leadQuery->whereDate('activity_date', '<=', $endDate);
            $customerQuery->whereDate('activity_date', '<=', $endDate);
        }

        $leadActivities = $leadQuery->get();
        $customerActivities = $customerQuery->get();
        
        $users = collect();
        if ($user->role === 'admin') {
            $users = User::whereIn('role', ['manager', 'sales_staff'])->get()->groupBy('role');
        } elseif ($user->role === 'manager') {
            $users = User::where('role', 'sales_staff')->get()->groupBy('role');
        }

        return view('activities.index', compact('leadActivities', 'customerActivities', 'users'));
    }

    public function create(Request $request)
    {
        $customers = Customer::all();
        $leads = Lead::all();

        return view('activities.create', compact('customers', 'leads'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'activity_type' => 'required',
            'description' => 'required',
            'activity_date' => 'required|date'
        ]);

        $activity = Activity::create([
            'customer_id' => $request->customer_id,
            'lead_id' => $request->lead_id,
            'user_id' => Auth::id(),
            'activity_type' => $request->activity_type,
            'description' => $request->description,
            'activity_date' => $request->activity_date
        ]);
        
        if ($activity->customer_id) 
            {
            $activity->customer->updateLastActivity();
            }
        if ($activity->lead_id) 
            {
            $activity->lead->updateLastActivity();
            }
        return redirect()->route('activities.index')->with('success', 'Activity logged successfully!');
    }
    public function destroy(Activity $activity)
    {
        if (Auth::user()->role !== 'admin' && Auth::id() !== $activity->user_id) {
            abort(403, 'Unauthorized action.');
        }
        $activity->delete();

        return redirect()->back()->with('success', 'Activity deleted successfully.');
    }
}
