<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\User; // Imported User model
use App\Models\Customer;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $search = $request->query('search');

        if (in_array($user->role, ['admin', 'manager'])) {
            $query = Lead::with('user');
        } else {
            $query = Lead::with('user')->where('assigned_user_id', $user->id);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('source', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $leads = $query->orderBy('created_at', 'desc')->get();
        return view('leads.index', compact('leads', 'search'));
    }

    public function create()
    {
        $admin = auth()->user();
        $users = \App\Models\User::whereIn('role', ['manager', 'sales_staff'])
            ->when($admin->role === 'manager', function ($q) {
                $q->where('role', 'sales_staff');
            })
            ->get();
        return view('leads.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
                'name'           => 'required|string|max:255',
                'email'          => 'nullable|email|max:255', 
                'status' => 'required|in:' . implode(',', array_keys(Lead::getStatuses())),
                'priority'       => 'required|in:Low,Medium,High',
                'expected_value' => 'nullable|numeric',
        ]);

        Lead::create([
            'name'             => $request->name,
            'email'            => $request->email,
            'phone'            => $request->phone,
            'source'           => $request->source ?? 'N/A',
            'status'           => $request->status, 
            'priority'         => $request->priority,
            'expected_value'   => $request->expected_value,
            'notes'            => $request->notes ?? 'N/A',
            'assigned_user_id' => $request->assigned_user_id ?? auth()->id() 
        ]);

        return redirect()->route('leads.index')->with('success', 'Lead created successfully!');
    }

    public function show(Lead $lead)
    {
        $activities = $lead->activities()->with('user')->latest()->get();
        $followUps = $lead->followUps()->latest()->get();
        return view('leads.show', compact('lead', 'activities', 'followUps'));
    }

    public function edit(Lead $lead)
    {
        $admin = auth()->user();
        $users = \App\Models\User::whereIn('role', ['manager', 'sales_staff'])
            ->when($admin->role === 'manager', function ($q) {
                $q->where('role', 'sales_staff');
            })
            ->get();
        return view('leads.edit', compact('lead', 'users'));
    }

    public function update(Request $request, Lead $lead)
    {

        $request->validate([
                'name'           => 'required|string|max:255',
                'email'          => 'nullable|email|max:255', 
                'status' => 'required|in:' . implode(',', array_keys(Lead::getStatuses())),
                'priority'       => 'required|in:Low,Medium,High',
                'expected_value' => 'nullable|numeric',
        ]);

        $lead->update($request->all());
        $lead->updateLastActivity();
        
        return redirect()->route('leads.index')->with('success', 'Lead updated successfully!');
    }

    public function updateStatus(Request $request, Lead $lead)
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(Lead::getStatuses()))
        ]);

        $lead->update(['status' => $request->status]);
        $lead->updateLastActivity();
        if ($lead->status === 'Won') {
            $this->performConversion($lead);
        }

        return response()->json(['success' => true]);
    }

    private function performConversion(Lead $lead)
    {
        $names = explode(' ', $lead->name);
        
        Customer::create([
            'first_name'       => $names[0] ?? 'N/A',
            'last_name'        => $names[1] ?? '', 
            'email'            => $lead->email,
            'phone'            => $lead->phone, 
            'company_name'     => 'N/A',
            'address'          => 'N/A',      
            'status'           => 'Active',            
            'assigned_user_id' => $lead->assigned_user_id
        ]);
    }

    public function convert(Lead $lead)
    {
        $lead->update(['status' => 'Won']);
        $lead->updateLastActivity();

        $this->performConversion($lead);

        return redirect()->route('customers.index')
                        ->with('success', 'Lead converted to Customer successfully!');
    }

    public function destroy(Lead $lead)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $lead->delete();
        return back()->with('success', 'Lead deleted.');
    }
}