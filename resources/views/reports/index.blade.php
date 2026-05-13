@section('title', 'Reports')
<x-app-layout>
    <div class="max-w-7xl mx-auto py-8">
        <div class="flex justify-between items-end mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Reports Overview</h1>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Performance Metrics & Pipeline</p>
            </div>
            
            <div class="flex gap-4">
                <form method="GET" class="flex flex-wrap items-end gap-4 mb-8">
                    <select name="staff_id" onchange="this.form.submit()" class="text-sm border-gray-200 rounded-md">
                        <option value="">All Staff</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ $staffId == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                    <input type="date" name="start_date" value="{{ $startDate }}" onchange="this.form.submit()" class="text-sm border-gray-200 rounded-md">
                    <input type="date" name="end_date" value="{{ $endDate }}" onchange="this.form.submit()" class="text-sm border-gray-200 rounded-md">
                    
                    <a href="?export=pdf&preview=true&staff_id={{ $staffId }}" 
                        target="_blank"
                        class="bg-teal-600 text-white px-4 py-2 rounded-md text-sm font-bold shadow-sm hover:bg-teal-700 transition">
                            Export PDF
                    </a>
                    <button type="submit" name="export_csv" value="1" class="bg-blue-600 text-white px-4 py-2 rounded text-sm font-bold">Export CSV</button>
                </form>

            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                <p class="text-[10px] uppercase font-bold text-gray-400 tracking-widest">Customers</p>
                <p class="text-3xl font-bold text-gray-800">{{ $data['totalCustomers'] }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                <p class="text-[10px] uppercase font-bold text-gray-400 tracking-widest">Total Leads</p>
                <p class="text-3xl font-bold text-teal-600">{{ $data['totalLeads'] }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                <p class="text-[10px] uppercase font-bold text-gray-400 tracking-widest">Completed Follow-ups</p>
                <p class="text-3xl font-bold text-green-600">{{ $data['completedFollowUps'] }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                <p class="text-[10px] uppercase font-bold text-gray-400 tracking-widest">Pending Follow-ups</p>
                <p class="text-3xl font-bold text-yellow-600">{{ $data['pendingFollowUps'] }}</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 mb-8">
    <h2 class="text-xs font-bold uppercase text-gray-500 tracking-wider mb-6">Lead Pipeline Distribution</h2>
    
    <div class="grid grid-cols-2 md:grid-cols-7 gap-4">
        <div class="bg-blue-500 rounded-lg p-4 text-center shadow-sm">
            <div class="text-[9px] font-bold uppercase text-white opacity-90 mb-2">New</div>
            <div class="text-2xl font-bold text-white">{{ $data['pipeline']['New'] ?? 0 }}</div>
        </div>

        <div class="bg-orange-500 rounded-lg p-4 text-center shadow-sm">
            <div class="text-[9px] font-bold uppercase text-white opacity-90 mb-2">Contacted</div>
            <div class="text-2xl font-bold text-white">{{ $data['pipeline']['Contacted'] ?? 0 }}</div>
        </div>

        <div class="bg-teal-500 rounded-lg p-4 text-center shadow-sm">
            <div class="text-[9px] font-bold uppercase text-white opacity-90 mb-2">Qualified</div>
            <div class="text-2xl font-bold text-white">{{ $data['pipeline']['Qualified'] ?? 0 }}</div>
        </div>

        <div class="bg-indigo-500 rounded-lg p-4 text-center shadow-sm">
            <div class="text-[9px] font-bold uppercase text-white opacity-90 mb-2">Proposal</div>
            <div class="text-2xl font-bold text-white">{{ $data['pipeline']['Proposal Sent'] ?? 0 }}</div>
        </div>

        <div class="bg-purple-500 rounded-lg p-4 text-center shadow-sm">
            <div class="text-[9px] font-bold uppercase text-white opacity-90 mb-2">Negotiation</div>
            <div class="text-2xl font-bold text-white">{{ $data['pipeline']['Negotiation'] ?? 0 }}</div>
        </div>

        <div class="bg-green-500 rounded-lg p-4 text-center shadow-sm">
            <div class="text-[9px] font-bold uppercase text-white opacity-90 mb-2">Won</div>
            <div class="text-2xl font-bold text-white">{{ $data['pipeline']['Won'] ?? 0 }}</div>
        </div>

        <div class="bg-red-500 rounded-lg p-4 text-center shadow-sm">
            <div class="text-[9px] font-bold uppercase text-white opacity-90 mb-2">Lost</div>
            <div class="text-2xl font-bold text-white">{{ $data['pipeline']['Lost'] ?? 0 }}</div>
        </div>
    </div>
</div>
        
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 mt-8">
        <h2 class="text-xs font-bold uppercase text-gray-500 tracking-wider mb-4">Detailed Activity Logs</h2>
        <table class="w-full text-left text-sm">
            <thead class="text-[10px] uppercase text-gray-400">
                <tr>
                    <th class="py-2">Staff</th>
                    <th class="py-2">Type</th> <th class="py-2">Action Details</th>
                    <th class="py-2">Time</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($data['activities'] ?? [] as $activity)
                    <tr>
                        <td class="py-3 font-bold">{{ $activity->user->name ?? 'System' }}</td>
                        <td class="py-3">
                            <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded text-[9px] uppercase font-bold">
                                {{ $activity->activity_type ?? 'General' }}
                            </span>
                        </td>
                        <td class="py-3 text-gray-600">
                            {{ $activity->description }} 
                            <span class="text-gray-400 italic text-[11px]">
                                {{ $activity->lead ? ' | Lead: ' . $activity->lead->name : ($activity->customer ? ' | Customer: ' . $activity->customer->first_name : '') }}
                            </span>
                        </td>
                        <td class="py-3 text-[10px] text-gray-400">{{ $activity->created_at->format('M d, Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 mt-8">
        <h2 class="text-xs font-bold uppercase text-gray-500 tracking-wider mb-4">Recently Completed Follow-ups</h2>
        <table class="w-full text-left text-sm">
            <thead class="text-[10px] uppercase text-gray-400">
                <tr>
                    <th class="py-2">Staff</th>
                    <th class="py-2">Task Details</th>
                    <th class="py-2">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($data['completedFollowUpDetails'] ?? [] as $f)
                    <tr>
                        <td class="py-3 font-bold">{{ $f->user->name ?? 'N/A' }}</td>
                        <td class="py-3">
                            <div class="font-bold text-gray-800">{{ $f->title }}</div>
                            <div class="text-[11px] text-gray-500 italic">{{ $f->description }}</div>
                        </td>
                        <td class="py-3">
                            @if($f->updated_at <= $f->due_date)
                                <span class="text-[9px] bg-green-100 text-green-700 px-2 py-0.5 rounded-full font-bold uppercase">On Time</span>
                            @else
                                <span class="text-[9px] bg-red-100 text-red-700 px-2 py-0.5 rounded-full font-bold uppercase">Overdue</span>
                            @endif
                            <div class="text-[10px] text-gray-400 mt-1">{{ $f->updated_at->format('M d, Y') }}</div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>