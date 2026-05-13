@section('title', 'Leads')
<x-app-layout>
    <div class="max-w-4xl mx-auto lg:mt-8">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6 px-2">
            <div class="flex items-center">
                <a href="{{ route('leads.index') }}" class="text-gray-400 hover:text-gray-600 transition mr-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
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
                        <p class="text-sm text-gray-700 font-medium break-all">{{ $lead->email }}</p>
                    </div>

                    <div>
                        <label class="block text-[11px] uppercase font-bold text-gray-400 mb-1">Contact Number</label>
                        <p class="text-sm text-gray-700 font-medium">{{ $lead->phone ?? 'Not provided' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-sm border border-gray-200 rounded-sm overflow-hidden">
                <div class="bg-gray-50/50 border-b border-gray-100 px-4 py-3">
                    <h2 class="text-[11px] uppercase font-bold text-teal-700 tracking-wider">Internal Notes</h2>
                </div>
                <div class="p-6">
                    <p class="text-sm text-gray-600 leading-relaxed italic">{{ $lead->notes ?? 'No additional notes recorded.' }}</p>
                </div>
            </div>

            @if(in_array(auth()->user()->role, ['admin', 'manager']))
            <div class="bg-white shadow-sm border border-gray-200 rounded-sm overflow-hidden">
                <div class="bg-gray-50/50 border-b border-gray-100 px-4 py-3">
                    <h2 class="text-[11px] uppercase font-bold text-teal-700 tracking-wider">Internal Assignment</h2>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-y-8">
                    <div>
                        <label class="block text-[11px] uppercase font-bold text-gray-400 mb-1">Assigned Personnel</label>
                        <p class="text-sm text-gray-700 font-medium">{{ $lead->user->name ?? 'Unassigned' }}</p>
                    </div>
                    <div>
                        <label class="block text-[11px] uppercase font-bold text-gray-400 mb-1">Staff ID</label>
                        <p class="text-sm text-gray-500 font-mono">#{{ $lead->assigned_user_id ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
            @endif

            <div class="bg-white p-6 rounded shadow-sm border border-gray-100">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-6">
                    <span>Created: {{ $lead->created_at->format('M d, Y H:i') }}</span>
                    <span>Last Update: {{ $lead->updated_at ? $lead->updated_at->diffForHumans() : 'N/A' }}</span>
                </div>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('leads.index') }}" class="text-sm font-semibold text-gray-400 hover:text-gray-600 transition text-center sm:mr-4">
                        Back to List
                    </a>
                    @if(!in_array($lead->status, ['Won', 'Lost']))
                        <a href="{{ route('leads.edit', $lead) }}" class="bg-teal-600 hover:bg-teal-700 text-white px-8 py-2.5 rounded-md text-sm font-bold transition shadow-md text-center">
                            Edit Lead
                        </a>
                    @else
                        <span class="bg-gray-100 text-gray-500 px-8 py-2.5 rounded-md text-sm font-bold border border-gray-200 text-center cursor-not-allowed">
                            Record Closed
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>