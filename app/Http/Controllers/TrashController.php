<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Lead;
use App\Models\Activity;
use App\Models\FollowUp;
use Illuminate\Http\Request;

class TrashController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->user()->role !== 'admin') abort(403);

        $tabs = [
            'customers'  => ['label' => 'Customers', 'color' => 'blue'],
            'leads'      => ['label' => 'Leads', 'color' => 'amber'],
            'activities' => ['label' => 'Activities', 'color' => 'purple'],
            'followups'  => ['label' => 'Follow-ups', 'color' => 'rose'],
        ];

        $activeType = $request->query('type', 'customers');
        
        $items = match($activeType) {
            'leads'      => Lead::onlyTrashed()->latest('deleted_at')->paginate(10),
            'activities' => Activity::onlyTrashed()->latest('deleted_at')->paginate(10),
            'followups'  => FollowUp::onlyTrashed()->latest('deleted_at')->paginate(10),
            default      => Customer::onlyTrashed()->latest('deleted_at')->paginate(10),
        };

       return view('trash.index', compact('items', 'activeType', 'tabs'));
    }

    public function restore(Request $request, $id)
    {
        $type = $request->input('type');
        $model = match($type) {
            'leads'      => Lead::onlyTrashed()->findOrFail($id),
            'activities' => Activity::onlyTrashed()->findOrFail($id),
            'followups'  => FollowUp::onlyTrashed()->findOrFail($id),
            default      => Customer::onlyTrashed()->findOrFail($id),
        };

        $model->restore();
        return back()->with('success', 'Item restored successfully.');
    }

    public function forceDelete(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') abort(403);

        $type = $request->input('type');
        $model = match($type) {
            'leads'      => Lead::onlyTrashed()->findOrFail($id),
            'activities' => Activity::onlyTrashed()->findOrFail($id),
            'followups'  => FollowUp::onlyTrashed()->findOrFail($id),
            default      => Customer::onlyTrashed()->findOrFail($id),
        };

        $model->forceDelete();
        return back()->with('success', 'Item permanently deleted.');
    }
}