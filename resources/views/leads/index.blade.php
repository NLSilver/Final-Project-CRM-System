@section('title', 'Leads')
<x-app-layout>
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 bg-white p-4 rounded-lg shadow-sm border border-gray-100">
        <div class="flex items-center space-x-4">
            <a href="{{ route('leads.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-md text-sm font-bold transition shadow-sm">
                + Add New Lead
            </a>
            
            <form action="{{ route('leads.index') }}" method="GET" class="flex items-center border-l pl-4 border-gray-200">
                <label class="text-[10px] font-bold uppercase text-gray-400 mr-2">Filter By Status:</label>
                <select name="status" onchange="this.form.submit()" 
                        class="text-xs border-gray-200 rounded focus:ring-teal-500 focus:border-teal-500 py-1">
                    <option value="">All Leads</option>
                    @foreach(['New', 'Contacted', 'Qualified', 'Proposal Sent', 'Negotiation', 'Won', 'Lost'] as $st)
                        <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ $st }}</option>
                    @endforeach
                </select>
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
            </form>
        </div>

        <form action="{{ route('leads.index') }}" method="GET" id="leadSearchForm" class="relative">
            <input type="text" name="search" id="leadSearchInput" value="{{ request('search') }}" 
                placeholder="Search name or source..." autocomplete="off"
                class="pl-9 pr-4 py-2 border-gray-200 rounded-md text-sm focus:ring-teal-500 w-64">
        </form>
    </div>

    <div class="bg-white shadow-sm border border-gray-200 rounded-sm overflow-hidden">
        <table class="min-w-full border-collapse">
            <thead>
                <tr class="text-gray-400 text-[11px] uppercase border-b border-gray-100 bg-gray-50/50">
                    <th class="px-4 py-3 text-left font-semibold">Prospect</th>
                    <th class="px-4 py-3 text-left font-semibold">Status</th>
                    <th class="px-4 py-3 text-left font-semibold">Priority</th>
                    <th class="px-4 py-3 text-left font-semibold">Value</th>
                    @if(auth()->user()->role !== 'sales_staff')
                        <th class="px-4 py-3 text-left font-semibold">Assigned To</th>
                    @endif
                    <th class="w-[15%] px-4 py-3 text-center font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($leads as $lead)
                    @php
                        $statusColors = [
                            'New' => 'bg-blue-50 text-blue-700 border-blue-200',
                            'Contacted' => 'bg-orange-50 text-orange-700 border-orange-200',
                            'Qualified' => 'bg-teal-50 text-teal-700 border-teal-200',
                            'Won' => 'bg-green-50 text-green-700 border-green-200',
                            'Lost' => 'bg-red-50 text-red-700 border-red-200',
                        ];
                        $colorClass = $statusColors[$lead->status] ?? 'bg-gray-50 text-gray-700 border-gray-200';
                    @endphp
                    <tr class="hover:bg-gray-50/50 transition group">
                        <td class="px-4 py-4 text-sm font-bold text-gray-800">
                            <a href="{{ route('leads.show', $lead) }}" class="hover:text-teal-600">
                                {{ $lead->name }}
                            </a>
                            <div class="text-[10px] text-gray-400 font-medium tracking-wide uppercase">{{ $lead->source ?? 'Direct' }}</div>
                        </td>
                        
                        <td class="px-4 py-4">
                            <select onchange="updateLeadStatus({{ $lead->id }}, this.value)" 
                                {{ $lead->status === 'Won' || $lead->status === 'Lost' ? 'disabled' : '' }} 
                                class="text-[10px] font-bold uppercase py-1 px-2 rounded-full border {{ $colorClass }} focus:ring-0 {{ $lead->status === 'Won' || $lead->status === 'Lost'? 'cursor-not-allowed opacity-80' : 'cursor-pointer' }}">
                             @foreach(['New', 'Contacted', 'Qualified', 'Proposal Sent', 'Negotiation', 'Won', 'Lost'] as $st)
                                <option value="{{ $st }}" {{ $lead->status == $st ? 'selected' : '' }}>{{ $st }}</option>
                            @endforeach
                            </select>
                        </td>

                        <td class="px-4 py-4">
                            <span class="text-[10px] font-bold uppercase {{ $lead->priority === 'High' ? 'text-red-600' : 'text-gray-400' }}">
                                {{ $lead->priority }}
                            </span>
                        </td>

                        <td class="px-4 py-4 text-sm font-mono font-bold text-gray-700">
                            ₱{{ number_format($lead->expected_value, 2) }}
                        </td>

                        @if(auth()->user()->role !== 'sales_staff')
                            <td class="px-4 py-3 border-r border-gray-100 truncate">
                                <div class="text-sm text-gray-700 truncate">{{ $lead->user->name ?? 'Unassigned' }}</div>
                                <div class="text-[10px] text-gray-400 uppercase font-bold">{{ $lead->user->role ?? 'N/A' }}</div>
                            </td>
                        @endif

                        <td class="px-4 py-3 text-sm">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('leads.show', $lead) }}" class="group">
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-teal-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>

                                @if($lead->status !== 'Won' && $lead->status !== 'Lost')
                                    <span class="text-gray-300">|</span>
                                    
                                    <a href="{{ route('leads.edit', $lead) }}" class="group">
                                        <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>

                                    <span class="text-gray-300">|</span>

                                    <form action="{{ route('leads.convert', $lead) }}" method="POST" class="flex items-center">
                                        @csrf
                                        <button type="submit" class="group" onclick="return confirm('Convert to Customer?')">
                                            <svg class="w-5 h-5 text-gray-400 group-hover:text-orange-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                            </svg>
                                        </button>
                                    </form>
                                @endif

                                <span class="text-gray-300">|</span>

                                <form action="{{ route('leads.destroy', $lead) }}" method="POST" class="flex items-center" onsubmit="return confirm('Delete this lead?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="group">
                                        <svg class="w-5 h-5 text-gray-400 group-hover:text-red-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center text-gray-400 italic text-sm">No leads match your criteria.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <script>
        function updateLeadStatus(id, newStatus) {
            fetch(`/leads/${id}/update-status`, {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json', 
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ status: newStatus })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    window.location.reload();
                }
            });
        }

        const leadInput = document.getElementById('leadSearchInput');
        const leadForm = document.getElementById('leadSearchForm');
        let leadTimeout = null;

        leadInput.addEventListener('input', function() {
            clearTimeout(leadTimeout);
            leadTimeout = setTimeout(() => {
                leadForm.submit();
            }, 450);
        });
    </script>
</x-app-layout>