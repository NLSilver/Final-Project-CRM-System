@section('title', 'Reports')
<x-app-layout>
    <div class="max-w-7xl mx-auto py-4 sm:py-8 lg:px-0">
        <div class="flex flex-col lg:flex-row lg:justify-between lg:items-end gap-6 mb-8 px-2">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Reports Overview</h1>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Performance Metrics & Pipeline</p>
            </div>
            
            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm w-full lg:w-auto">
                <form id="reportFilterForm" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:flex lg:items-end gap-4">
                    <div class="flex flex-col gap-1">
                        <label class="text-[10px] font-bold text-gray-400 uppercase">Staff Member</label>
                        @if(in_array(auth()->user()->role, ['admin', 'manager']))
                            <select name="staff_id" onchange="this.form.submit()" class="w-full text-sm border-gray-200 rounded-md focus:ring-teal-500">
                                <option value="">All Staff</option>
                                @foreach($users as $role => $roleUsers)
                                    <optgroup label="{{ ucfirst(str_replace('_', ' ', $role)) }}">
                                        @foreach($roleUsers as $u)
                                            <option value="{{ $u->id }}" {{ $staffId == $u->id ? 'selected' : '' }}>
                                                {{ $u->name }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        @else
                            <div class="text-sm font-bold text-gray-700 bg-gray-50 px-3 py-2 rounded-md border border-gray-200">
                                {{ auth()->user()->name }}
                            </div>
                            <input type="hidden" name="staff_id" value="{{ auth()->id() }}">
                        @endif
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="text-[10px] font-bold text-gray-400 uppercase">Start Date</label>
                        <input type="date" name="start_date" value="{{ $startDate }}" onchange="this.form.submit()" class="text-sm border-gray-200 rounded-md focus:ring-teal-500">
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="text-[10px] font-bold text-gray-400 uppercase">End Date</label>
                        <input type="date" name="end_date" value="{{ $endDate }}" onchange="this.form.submit()" class="text-sm border-gray-200 rounded-md focus:ring-teal-500">
                    </div>
                    
                    <div class="flex gap-2 lg:pb-0.5">
                        <a href="{{ route('reports.index', array_merge(request()->all(), ['export' => 'pdf', 'preview' => 'true'])) }}" 
                            target="_blank"
                            class="flex-1 lg:flex-none text-center bg-teal-600 text-white px-4 py-2 rounded-md text-sm font-bold shadow-sm hover:bg-teal-700 transition">
                                PDF
                        </a>
                        <button type="submit" name="export_csv" value="1" class="flex-1 lg:flex-none bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-bold shadow-sm hover:bg-blue-700 transition">
                            CSV
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8 px-2">
            <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border border-gray-100">
                <p class="text-[9px] sm:text-[10px] uppercase font-bold text-gray-400 tracking-widest">Customers</p>
                <p class="text-2xl sm:text-3xl font-bold text-gray-800">{{ $data['totalCustomers'] }}</p>
            </div>
            <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border border-gray-100">
                <p class="text-[9px] sm:text-[10px] uppercase font-bold text-gray-400 tracking-widest">Total Leads</p>
                <p class="text-2xl sm:text-3xl font-bold text-teal-600">{{ $data['totalLeads'] }}</p>
            </div>
            <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border border-gray-100">
                <p class="text-[9px] sm:text-[10px] uppercase font-bold text-gray-400 tracking-widest">Done Tasks</p>
                <p class="text-2xl sm:text-3xl font-bold text-green-600">{{ $data['completedFollowUps'] }}</p>
            </div>
            <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border border-gray-100">
                <p class="text-[9px] sm:text-[10px] uppercase font-bold text-gray-400 tracking-widest">Pending</p>
                <p class="text-2xl sm:text-3xl font-bold text-yellow-600">{{ $data['pendingFollowUps'] }}</p>
            </div>
        </div>

        <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border border-gray-100 mb-8 mx-2">
            <h2 class="text-xs font-bold uppercase text-gray-500 tracking-wider mb-6">Lead Pipeline Distribution</h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-3 sm:gap-4">
                @foreach([
                    'New' => 'bg-blue-500',
                    'Contacted' => 'bg-orange-500',
                    'Qualified' => 'bg-teal-500',
                    'Proposal Sent' => 'bg-indigo-500',
                    'Negotiation' => 'bg-purple-500',
                    'Won' => 'bg-green-500',
                    'Lost' => 'bg-red-500'
                ] as $label => $bg)
                    <div class="{{ $bg }} rounded-lg p-3 sm:p-4 text-center shadow-sm transition-transform hover:scale-105">
                        <div class="text-[8px] sm:text-[9px] font-bold uppercase text-white opacity-90 mb-1 sm:mb-2 truncate">
                            {{ $label === 'Proposal Sent' ? 'Proposal' : $label }}
                        </div>
                        <div class="text-xl sm:text-2xl font-bold text-white">{{ $data['pipeline'][$label] ?? 0 }}</div>
                    </div>
                @endforeach
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 mt-8 mx-2 overflow-hidden">
            <div class="p-4 sm:p-6 border-b border-gray-50">
                <h2 class="text-xs font-bold uppercase text-gray-500 tracking-wider">Detailed Activity Logs</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm min-w-[600px]">
                    <thead class="bg-gray-50 text-[10px] uppercase text-gray-400">
                        <tr>
                            <th class="px-6 py-3">Staff</th>
                            <th class="px-6 py-3">Type</th> 
                            <th class="px-6 py-3">Action Details</th>
                            <th class="px-6 py-3 whitespace-nowrap">Time</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($data['activities'] ?? [] as $activity)
                            <tr class="hover:bg-gray-50/50">
                                <td class="px-6 py-4 font-bold text-gray-700">{{ $activity->user->name ?? 'System' }}</td>
                                <td class="px-6 py-4">
                                    <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded text-[9px] uppercase font-bold">
                                        {{ $activity->activity_type ?? 'General' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    <div class="line-clamp-1 sm:line-clamp-none">{{ $activity->description }}</div>
                                    <span class="text-gray-400 italic text-[10px] block mt-1">
                                        {{ $activity->lead ? 'Lead: ' . $activity->lead->name : ($activity->customer ? 'Customer: ' . $activity->customer->first_name : '') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-[10px] text-gray-400 whitespace-nowrap">{{ $activity->created_at->format('M d, Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-100 mt-8 mx-2 overflow-hidden">
            <div class="p-4 sm:p-6 border-b border-gray-50">
                <h2 class="text-xs font-bold uppercase text-gray-500 tracking-wider">Recently Completed Follow-ups</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm min-w-[600px]">
                    <thead class="bg-gray-50 text-[10px] uppercase text-gray-400">
                        <tr>
                            <th class="px-6 py-3">Staff</th>
                            <th class="px-6 py-3">Task Details</th>
                            <th class="px-6 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($data['completedFollowUpDetails'] ?? [] as $f)
                            <tr class="hover:bg-gray-50/50">
                                <td class="px-6 py-4 font-bold text-gray-700">{{ $f->user->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-800">{{ $f->title }}</div>
                                    <div class="text-[11px] text-gray-500 italic line-clamp-1">{{ $f->description }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php $onTime = $f->updated_at <= $f->due_date; @endphp
                                    <span class="text-[9px] {{ $onTime ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} px-2 py-0.5 rounded-full font-bold uppercase">
                                        {{ $onTime ? 'On Time' : 'Overdue' }}
                                    </span>
                                    <div class="text-[10px] text-gray-400 mt-1">{{ $f->updated_at->format('M d, Y') }}</div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>