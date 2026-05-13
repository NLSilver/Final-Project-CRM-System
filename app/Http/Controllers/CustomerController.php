<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $search = $request->query('search');

        if (in_array($user->role, ['admin', 'manager'])) {
            $query = Customer::with('user');
        } else {
        $query = Customer::with('user')->where('assigned_user_id', $user->id);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $allCustomers = $query->get();
        $customers = $allCustomers->groupBy('status');
        $activeCount = $allCustomers->where('status', 'Active')->count();
        $inactiveCount = $allCustomers->where('status', 'Inactive')->count();

        return view('customers.index', compact('customers', 'activeCount', 'inactiveCount', 'search'));
    }

    public function create()
    {
        $admin = auth()->user();
        $staff = \App\Models\User::whereIn('role', ['manager', 'sales_staff'])
            ->when($admin->role === 'manager', function ($q) {
                $q->where('role', 'sales_staff');
            })
            ->get();
            
        return view('customers.create', compact('staff'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'phone'      => 'required|string',
            'email'      => 'required|email|unique:customers,email,' . ($customer->id ?? ''), 
        ]);
        Customer::create([
            'first_name'       => $request->first_name,
            'last_name'        => $request->last_name,
            'email'            => $request->email,
            'phone'            => $request->phone,
            'company_name'     => $request->company_name ?? 'N/A',
            'address'          => $request->address ?? 'N/A',      
            'status'           => $request->status,
            'assigned_user_id' => $request->assigned_user_id ?? auth()->id()
        ]);

        return redirect()->route('customers.index');
    }

    public function show(Customer $customer)
    {
        $activities = $customer->activities()->with('user')->latest()->get();
        $followUps = $customer->followUps()->latest()->get();
        return view('customers.show', compact('customer', 'activities', 'followUps'));
    }

    public function edit(Customer $customer)
    {
        $admin = auth()->user();
        $staff = \App\Models\User::whereIn('role', ['manager', 'sales_staff'])
            ->when($admin->role === 'manager', function ($q) {
                $q->where('role', 'sales_staff');
            })
            ->get();
        return view('customers.edit', compact('customer', 'staff'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'phone'      => 'required|string',
            'email'      => 'required|email|unique:customers,email,' . ($customer->id ?? ''), 
        ]);
        
        $customer->update($request->all());
        $customer->updateLastActivity();
        return redirect()->route('customers.index');
    }

    public function destroy(Customer $customer)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }
    
        $customer->delete();
        return back();
    }
    
}