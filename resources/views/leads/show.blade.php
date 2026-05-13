@section('title', 'Leads')
<x-app-layout>
    <div class="max-w-4xl mx-auto mt-8">
        <div class="flex items-center justify-between mb-6 px-2">
            <div class="flex items-center">
                <a href="{{ route('leads.index') }}" class="text-gray-400 hover:text-gray-600 transition mr-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-gray-800 tracking-tight">{{ $lead->name }}</h1>
                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wider italic">Lead Opportunity • Source: {{ $lead->source ?? 'Unknown' }}</p>
                </div>
            </div>
            
            <div class="flex items-center">
                @php
                    $statusColors = [
                        'New'           => 'bg-blue-50 text-blue-700 border-blue-200',
                        'Contacted'     => 'bg-orange-50 text-orange-700 border-orange-200',
                        'Qualified'     => 'bg-teal-50 text-teal-700 border-teal-200',
                        'Proposal Sent' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                        'Negotiation'   => 'bg-purple-50 text-purple-700 border-purple-200',
                        'Won'           => 'bg-green-50 text-green-700 border-green-200',
                        'Lost'          => 'bg-red-50 text-red-700 border-red-200'
                    ];
                    $currentClass = $statusColors[$lead->status] ?? 'bg-gray-50 text-gray-700 border-gray-200';
                @endphp
                <span class="px-3 py-1 border rounded-full text-[10px] font-bold uppercase tracking-widest {{ $currentClass }}">
                    {{ $lead->status }}
                </span>
            </div>
        </div>

        <div class="space-y-6 mb-10">
            <div class="bg-white shadow-sm border border-gray-200 rounded-sm overflow-hidden">
                <div class="bg-gray-50/50 border-b border-gray-100 px-4 py-3">
                    <h2 class="text-[11px] uppercase font-bold text-teal-700 tracking-wider">Opportunity Details</h2>
                </div>
                
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
                    <div>
                        <label class="block text-[11px] uppercase font-bold text-gray-400 mb-1">Expected Deal Value</label>
                        <p class="text-lg font-bold text-gray-800 font-mono">₱{{ number_format($lead->expected_value, 2) }}</p>
                    </div>

                    <div>
                        <label class="block text-[11px] uppercase font-bold text-gray-400 mb-1">Priority Level</label>
                        <p class="text-sm font-bold uppercase {{ $lead->priority === 'High' ? 'text-red-600' : 'text-gray-600' }}">
                            {{ $lead->priority }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-[11px] uppercase font-bold text-gray-400 mb-1">Email Address</label>
                        <p class="text-sm text-gray-700 font-medium">{{ $lead->email }}</p>
                    </div>

                    <div>
                        <label class="block text-[11px] uppercase font-bold text-gray-400 mb-1">Contact Number</label>
                        <p class="text-sm text-gray-700 font-medium">{{ $lead->phone ?? 'Not provided' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-sm border border-gray-200 rounded-sm overflow-hidden">
                <div class="bg-gray-50/50 border-b border-gray-100 px-4 py-3">
                    <h2 class="text-[11px] uppercase font-bold text-teal-700 tracking-wider">Internal Notes & Context</h2>
                </div>
                <div class="p-6">
                    <p class="text-sm text-gray-600 leading-relaxed italic">
                        {{ $lead->notes ?? 'No additional notes provided for this lead.' }}
                    </p>
                </div>
            </div>

            @if(in_array(auth()->user()->role, ['admin', 'manager']))
            <div class="bg-white shadow-sm border border-gray-200 rounded-sm overflow-hidden">
                <div class="bg-gray-50/50 border-b border-gray-100 px-4 py-3">
                    <h2 class="text-[11px] uppercase font-bold text-teal-700 tracking-wider">Internal Assignment</h2>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-x-12">
                    <div>
                        <label class="block text-[11px] uppercase font-bold text-gray-400 mb-1">Assigned Sales Personnel</label>
                        <p class="text-sm text-gray-700 font-medium">{{ $lead->user->name ?? 'Unassigned' }}</p>
                    </div>
                    <div>
                        <label class="block text-[11px] uppercase font-bold text-gray-400 mb-1">Staff User ID</label>
                        <p class="text-sm text-gray-500 font-mono">#{{ $lead->assigned_user_id ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
            @endif

            <div class="mt-8">
                <div class="mt-10">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-[11px] font-bold uppercase tracking-widest text-teal-700">Recent Activities</h3>
                    @if(!in_array($lead->status, ['Won', 'Lost']))
                    <a href="{{ route('activities.create', [
                        'type' => isset($lead) ? 'lead' : 'customer', 
                        'id' => $lead->id ?? $customer->id
                    ]) }}" 
                    class="text-[10px] font-bold text-teal-600 hover:text-teal-800 uppercase tracking-widest">
                    + Log New Activity
                    </a>
                    @endif
                </div>

                <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-100">
                        <tbody class="divide-y divide-gray-50">
                            @forelse($activities as $activity)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3 mb-1">
                                            <span class="px-2 py-0.5 text-[9px] font-bold uppercase rounded-full 
                                                @if($activity->activity_type == 'call') bg-green-100 text-green-700
                                                @elseif($activity->activity_type == 'email') bg-blue-100 text-blue-700
                                                @elseif($activity->activity_type == 'meeting') bg-purple-100 text-purple-700
                                                @else bg-gray-100 text-gray-700 @endif">
                                                {{ $activity->activity_type }}
                                            </span>
                                            <span class="text-[10px] text-gray-400 font-medium tracking-wider">
                                                {{ $activity->activity_date }}
                                            </span>
                                        </div>
                                        
                                        <p class="text-sm font-bold text-gray-800">{{ $activity->title ?? 'Activity Logged' }}</p>
                                        <p class="text-xs text-gray-500 italic mt-1">{{ $activity->description }}</p>
                                        
                                        <div class="mt-2">
                                            <span class="text-[10px] text-teal-600 font-bold uppercase">Staff: {{ $activity->user->name ?? 'Unknown' }}</span>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td class="px-6 py-8 text-center text-xs text-gray-400 italic">No activities recorded.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-10">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-[11px] font-bold uppercase tracking-widest text-teal-700">Recent Follow-ups</h3>
                    @if(!in_array($lead->status, ['Won', 'Lost']))
                    <a href="{{ route('follow-ups.create', [
                        'type' => isset($lead) ? 'lead' : 'customer', 
                        'id' => $lead->id ?? $customer->id
                        ]) }}" 
                    class="text-[10px] font-bold text-teal-600 hover:text-teal-800 uppercase">
                    + Schedule New
                    </a>
                    @endif
                </div>

                <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-100">
                        <tbody class="divide-y divide-gray-50">
                            @forelse($followUps as $f)
                                @php $isOverdue = $f->status === 'pending' && \Carbon\Carbon::parse($f->due_date)->isPast(); @endphp
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3 mb-1">
                                            <span class="text-[10px] font-bold uppercase {{ $isOverdue ? 'text-red-500' : 'text-gray-400' }}">
                                                Due: {{ $f->due_date }} {{ $isOverdue ? '(Overdue)' : '' }}
                                            </span>
                                        </div>
                                        <p class="text-sm font-bold text-gray-800">{{ $f->title }}</p>
                                        <p class="text-xs text-gray-500 italic">{{ $f->description }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        @if($f->status === 'pending')
                                            <form method="POST" action="{{ route('follow-ups.complete', $f->id) }}">
                                                @csrf
                                                <button class="text-[10px] font-bold text-green-600 uppercase hover:underline">Done</button>
                                            </form>
                                        @else
                                            <span class="text-[10px] font-bold text-green-600 uppercase">Completed</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td class="px-6 py-6 text-center text-xs text-gray-400 italic">No follow-ups found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="px-2 flex justify-between items-center text-[10px] text-gray-400 uppercase font-bold tracking-widest">
                <span>Created: {{ $lead->created_at->format('M d, Y H:i') }}</span>
                <span>Last Activity: {{ $lead->updated_at ? $lead->updated_at->diffForHumans() . ' (' . $lead->updated_at->format('M d, Y h:i A') . ')' : 'No activity yet' }}</span>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-100">
                <a href="{{ route('leads.index') }}" class="text-sm font-semibold text-gray-400 hover:text-gray-600 transition">
                    Back to List
                </a>
                @if($lead->status !== 'Won' && $lead->status !== 'Lost')
                    <a href="{{ route('leads.edit', $lead) }}" class="bg-teal-600 text-white px-4 py-2 rounded-md">
                        Edit Lead
                    </a>
                @elseif($lead->status !== 'Won' && $lead->status !== 'Lost' && $lead->trashed())
                    <button disabled class="bg-gray-100 text-gray-400 px-8 py-2.5 rounded-md text-sm font-bold cursor-not-allowed border border-gray-200">
                        Lead is Trashed
                    </button>
                @elseif($lead->status !== 'Lost')
                    <span class="bg-green-100 text-green-800 px-4 py-2 rounded-md font-bold uppercase text-xs border border-green-200">
                        Lead Already Won
                    </span>
                @else
                    <span class="bg-red-100 text-red-800 px-4 py-2 rounded-md font-bold uppercase text-xs border border-green-200">
                        Lead Already Lost
                    </span>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>