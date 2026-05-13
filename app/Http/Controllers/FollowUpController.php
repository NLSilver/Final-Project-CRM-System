<?php

namespace App\Http\Controllers;

use App\Models\FollowUp;
use App\Models\Customer;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowUpController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->query('search');
        $userIdFilter = $request->query('user_id');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $query = FollowUp::with(['customer', 'lead', 'user']);

        if (in_array($user->role, ['admin', 'manager'])) {
            if ($userIdFilter) {
                $query->where('user_id', $userIdFilter);
            }
            $users = \App\Models\User::whereIn('role', ['manager', 'sales_staff'])
                        ->get()->groupBy('role');
        } else {
            $query->where('user_id', $user->id);
            $users = collect();
        }

        if ($startDate) $query->whereDate('due_date', '>=', $startDate);
        if ($endDate) $query->whereDate('due_date', '<=', $endDate);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                ->orWhereHas('customer', fn($q) => $q->where('first_name', 'like', "%$search%")->orWhere('last_name', 'like', "%$search%"))
                ->orWhereHas('lead', fn($q) => $q->where('name', 'like', "%$search%"));
            });
        }

        $followUps = $query->latest()->get();
        return view('follow-ups.index', compact('followUps', 'users'));
    }

    public function create()
    {
        $customers = Customer::all();
        $leads = Lead::all();

        return view('follow-ups.create', compact('customers','leads'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'due_date' => 'required|date'
        ]);

        $customer = Customer::find($request->customer_id);
        $lead = Lead::find($request->lead_id);
        $assignedUserId = $customer?->assigned_user_id ?? $lead?->assigned_user_id ?? Auth::id();

        $followUp = FollowUp::create([
            'customer_id' => $request->customer_id,
            'lead_id' => $request->lead_id,
            'user_id' => $assignedUserId,
            'assigned_by' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description ?? 'N/A',
            'due_date' => $request->due_date,
            'status' => 'pending'
        ]);
        
        if ($followUp->customer_id) 
            {
                $followUp->customer->updateLastActivity();
            }
        if ($followUp->lead_id) 
            {
                $followUp->lead->updateLastActivity();
            }

        return redirect()->route('follow-ups.index');
    }

    public function edit(FollowUp $followUp)
    {
        $customers = Customer::all();
        $leads = Lead::all();

        return view('follow-ups.edit', compact('followUp','customers','leads'));
    }

    public function update(Request $request, FollowUp $followUp)
    {
        $request->validate([
            'title' => 'required',
            'due_date' => 'required|date'
        ]);

        $followUp->update([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'customer_id' => $request->customer_id,
            'lead_id' => $request->lead_id,
        ]);

        if ($followUp->customer_id) 
            {
                $followUp->customer->updateLastActivity();
            }
        if ($followUp->lead_id) 
            {
                $followUp->lead->updateLastActivity();
            }
            
        return redirect()->route('follow-ups.index');
    }

    public function markComplete(Request $request, FollowUp $followUp)
    {
        if ($followUp->customer_id) 
            {
                $followUp->customer->updateLastActivity();
            }
        if ($followUp->lead_id) 
            {
                $followUp->lead->updateLastActivity();
            }

        $followUp->update(['status' => 'completed']);
        return back();
    }

    public function destroy(FollowUp $followUp)
    {
        $followUp->delete();
        return back()->with('success', 'Follow-up deleted.');
    }
}