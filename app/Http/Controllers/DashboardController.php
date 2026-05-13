<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Lead;
use App\Models\FollowUp;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isAdminOrManager = in_array($user->role, ['admin', 'manager']);
        $closedStatuses = ['Won', 'Lost'];

        if ($isAdminOrManager) {
            $customersCount = Customer::count();
            $activeLeadsCount = Lead::whereNotIn('status', $closedStatuses)->count();
            $completedFollowUpsCount = FollowUp::where('status', 'completed')->count();
            $recentActivities = Activity::with(['user', 'customer', 'lead'])
                ->latest()
                ->limit(5)
                ->get();
            $upcomingFollowUps = FollowUp::with(['user', 'customer', 'lead'])
                ->where('status', 'pending')
                ->where('due_date', '>=', now())
                ->orderBy('due_date', 'asc')
                ->limit(5)
                ->get();
        } else {
            $customersCount = Customer::where('assigned_user_id', $user->id)->count();
            $activeLeadsCount = Lead::where('assigned_user_id', $user->id)
                ->whereNotIn('status', $closedStatuses)
                ->count();            
            $completedFollowUpsCount = FollowUp::where('user_id', $user->id)
                ->where('status', 'completed')
                ->count();
            $recentActivities = Activity::with(['user', 'customer', 'lead'])
                ->where('user_id', $user->id)
                ->latest()
                ->limit(5)
                ->get();
            $upcomingFollowUps = FollowUp::with(['user', 'customer', 'lead'])
                ->where('user_id', $user->id)
                ->where('status', 'pending')
                ->where('due_date', '>=', now())
                ->orderBy('due_date', 'asc')
                ->limit(5)
                ->get();
        }

        return view('dashboard', compact(
            'customersCount', 
            'activeLeadsCount', 
            'completedFollowUpsCount', 
            'recentActivities', 
            'upcomingFollowUps'
        ));
    }
}