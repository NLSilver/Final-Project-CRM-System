@section('title', 'Follow-Ups')
<x-app-layout>
        
        <div class="flex justify-between items-center mb-6 bg-white p-4 rounded-lg shadow-sm border border-gray-100">
            <div class="flex items-center space-x-6">
                <a href="{{ route('follow-ups.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-md text-sm font-bold transition shadow-sm">
                    + Add Follow-up
                </a>
            </div>

            <form action="{{ route('follow-ups.index') }}" method="GET" id="searchForm" class="flex items-center gap-4">
                <input type="text" name="search" id="searchInput" value="{{ request('search') }}" 
                    placeholder="Search..." class="text-xs border-gray-200 rounded w-48 focus:ring-0 focus:border-teal-500">

                <input type="date" name="start_date" value="{{ request('start_date') }}" 
                    onchange="this.form.submit()" class="text-xs border-gray-200 rounded focus:ring-0">
                <input type="date" name="end_date" value="{{ request('end_date') }}" 
                    onchange="this.form.submit()" class="text-xs border-gray-200 rounded focus:ring-0">

                @if(in_array(auth()->user()->role, ['admin', 'manager']))
                    <select name="user_id" onchange="this.form.submit()" class="text-xs border-gray-200 rounded focus:ring-0">
                        <option value="">All Staff</option>
                        @foreach($users as $role => $roleUsers)
                            <optgroup label="{{ ucfirst(str_replace('_', ' ', $role)) }}">
                                @foreach($roleUsers as $u)
                                    <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                                        {{ $u->name }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                @endif

                @if(request()->anyFilled(['search', 'start_date', 'end_date', 'user_id']))
                    <a href="{{ route('follow-ups.index') }}" class="text-[10px] font-bold text-red-500 uppercase hover:underline">Clear</a>
                @endif
            </form>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-100">
                <tbody class="divide-y divide-gray-50">
                    @forelse($followUps as $f)
                        @php $isOverdue = $f->status === 'pending' && \Carbon\Carbon::parse($f->due_date)->isPast(); @endphp
                        <tr class="hover:bg-gray-50/50 transition group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3 mb-1">
                                    <span class="px-2 py-0.5 text-[9px] font-bold uppercase rounded-full {{ $f->customer_id ? 'bg-blue-100 text-blue-700' : 'bg-teal-100 text-teal-700' }}">
                                        {{ $f->customer_id ? 'Customer' : 'Lead' }}
                                    </span>
                                    <span class="text-[10px] font-bold uppercase {{ $isOverdue ? 'text-red-500 animate-pulse' : 'text-gray-400' }}">
                                        Due: {{ $f->due_date }} {{ $isOverdue ? '(Overdue)' : '' }}
                                    </span>
                                </div>
                                
                                <p class="text-sm font-bold text-gray-800">
                                    @if($f->customer)
                                        <span class="text-blue-600">{{ $f->customer->first_name }} {{ $f->customer->last_name }}</span>
                                    @elseif($f->lead)
                                        <span class="text-teal-600">{{ $f->lead->name }}</span>
                                    @else
                                        <span class="text-gray-400">No Contact</span>
                                    @endif
                                    <span class="mx-2 text-gray-300">|</span>
                                    {{ $f->title }}
                                </p>
                                
                                <p class="text-xs text-gray-500 italic mt-1">{{ $f->description }}</p>
                                
                                <div class="mt-2 flex items-center gap-3">
                                    <span class="text-[10px] text-teal-600 font-bold uppercase">
                                        Staff: {{ $f->user->name ?? 'Unassigned' }}
                                    </span>
                                    <span class="text-gray-300">|</span>
                                    <span class="text-[10px] text-gray-400 font-bold uppercase">
                                        Assigned By: {{ $f->assignedBy->name ?? 'System' }}
                                    </span>
                                            @if($f->status === 'completed')
                                                <span class="text-[9px] bg-green-100 text-green-700 px-2 py-0.5 rounded-full font-bold uppercase">
                                                    ✓ Completed
                                                </span>
                                            @else
                                                <span class="text-[9px] bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded-full font-bold uppercase">
                                                    Pending
                                                </span>
                                            @endif
                                </div>
                            </td>
                            
                            <td class="px-4 py-3 text-sm whitespace-nowrap">
                                <div class="flex items-center justify-end gap-x-3">
                                    @if($f->status === 'pending')
                                        <form method="POST" action="{{ route('follow-ups.complete', $f->id) }}" class="flex items-center">
                                            @csrf
                                            <button type="submit" class="group" title="Mark as Done">
                                                <svg class="w-5 h-5 text-gray-400 group-hover:text-green-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </button>
                                        </form>

                                        <a href="{{ route('follow-ups.edit', $f->id) }}" class="group" title="Edit">
                                            <svg class="w-5 h-5 text-gray-400 group-hover:text-teal-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                    @endif
                                    
                                    <form method="POST" action="{{ route('follow-ups.destroy', $f->id) }}" class="flex items-center" onsubmit="return confirm('Delete this follow-up?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="group" title="Delete">
                                            <svg class="w-5 h-5 text-gray-400 group-hover:text-red-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td class="px-6 py-12 text-center text-sm text-gray-400 italic">No follow-ups match your search.</td></tr>
                    @endforelse
                    
                </tbody>
            </table>
        </div>
    </div>

    <script>
        const searchInput = document.getElementById('searchInput');
        const searchForm = document.getElementById('searchForm');
        let timeout = null;

        searchInput.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                searchForm.submit();
            }, 450);
        });

        window.addEventListener('load', () => {
            if (searchInput.value.length > 0) {
                const val = searchInput.value;
                searchInput.value = '';
                searchInput.focus();
                searchInput.value = val;
            }
        });
    </script>
</x-app-layout>