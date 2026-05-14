@section('title', 'Follow-Ups')
<x-app-layout>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6 bg-white p-4 rounded-lg shadow-sm border border-gray-100">
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 w-full md:w-auto">
            <a href="{{ route('follow-ups.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-md text-sm font-bold transition shadow-sm text-center">
                + Add Follow-up
            </a>
        </div>

        <form action="{{ route('follow-ups.index') }}" method="GET" id="searchForm" class="grid grid-cols-1 sm:grid-cols-2 lg:flex items-center gap-3 w-full md:w-auto">
            <input type="text" name="search" id="searchInput" value="{{ request('search') }}" 
                placeholder="Search..." class="text-xs border-gray-200 rounded focus:ring-0 focus:border-teal-500 w-full lg:w-40">

            <div class="flex items-center gap-2">
                <input type="date" name="start_date" value="{{ request('start_date') }}" 
                    onchange="this.form.submit()" class="text-xs border-gray-200 rounded focus:ring-0 w-full">
                <input type="date" name="end_date" value="{{ request('end_date') }}" 
                    onchange="this.form.submit()" class="text-xs border-gray-200 rounded focus:ring-0 w-full">
            </div>

            @if(in_array(auth()->user()->role, ['admin', 'manager']))
                <select name="user_id" onchange="this.form.submit()" class="text-xs border-gray-200 rounded focus:ring-0 w-full lg:w-32">
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
                <a href="{{ route('follow-ups.index') }}" class="text-[10px] font-bold text-red-500 uppercase hover:underline whitespace-nowrap text-center">Clear Filters</a>
            @endif
        </form>
    </div>

    <div class="mb-12">
        <h2 class="px-2 mb-3 text-[10px] font-bold uppercase text-amber-600 tracking-widest flex items-center">
            <span class="mr-2">●</span> Pending Tasks (Nearest Due First)
        </h2>
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-100">
                <tbody class="divide-y divide-gray-50">
                    @forelse($pendingFollowUps as $f)
                        @php $isOverdue = \Carbon\Carbon::parse($f->due_date)->isPast(); @endphp
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3 mb-1">
                                    <span class="text-[10px] font-bold uppercase {{ $isOverdue ? 'text-red-500 animate-pulse' : 'text-amber-500' }}">
                                        Due: {{ $f->due_date }} {{ $isOverdue ? '(OVERDUE)' : '' }}
                                    </span>
                                </div>
                                <p class="text-sm font-bold text-gray-800">
                                    {{ $f->customer->full_name ?? $f->lead->name ?? 'Contact' }} 
                                    <span class="mx-2 text-gray-300">|</span> 
                                    {{ $f->title }}
                                </p>
                                <p class="text-xs text-gray-500 italic mt-1">{{ $f->description }}</p>
                                <div class="mt-2 text-[10px] text-gray-400 font-bold uppercase">
                                    Assigned to: {{ $f->user->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <form method="POST" action="{{ route('follow-ups.complete', $f->id) }}">
                                        @csrf
                                        <button type="submit" class="text-gray-400 hover:text-green-600 transition" title="Complete">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </button>
                                    </form>
                                    <a href="{{ route('follow-ups.edit', $f->id) }}" class="text-gray-400 hover:text-teal-600 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('follow-ups.destroy', $f->id) }}" onsubmit="return confirm('Delete permanently?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-600 transition">
                                            <svg class="w-5 h-5 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-6 py-8 text-center text-xs text-gray-400 italic">No pending follow-ups found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-4 py-3 bg-gray-50 border-t border-gray-100">
                {{ $pendingFollowUps->appends(request()->except('pending_page'))->links() }}
            </div>
        </div>
    </div>

    <div>
        <h2 class="px-2 mb-3 text-[10px] font-bold uppercase text-green-600 tracking-widest flex items-center">
            <span class="mr-2">✓</span> Recently Completed (Latest First)
        </h2>
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden opacity-75">
            <table class="min-w-full divide-y divide-gray-100">
                <tbody class="divide-y divide-gray-50">
                    @forelse($completedFollowUps as $f)
                        <tr class="bg-gray-50/30">
                            <td class="px-6 py-4">
                                <div class="text-[10px] font-bold uppercase text-gray-400">
                                    Finished: {{ $f->updated_at->format('M d, Y') }}
                                </div>
                                <p class="text-sm font-bold text-gray-600 line-through decoration-gray-400">
                                    {{ $f->customer->full_name ?? $f->lead->name ?? 'Contact' }} 
                                    <span class="mx-2 text-gray-300">|</span> 
                                    {{ $f->title }}
                                </p>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <form method="POST" action="{{ route('follow-ups.destroy', $f->id) }}" onsubmit="return confirm('Delete permanently?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-600 transition">
                                        <svg class="w-5 h-5 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-6 py-8 text-center text-xs text-gray-400 italic">No completed follow-ups on this page.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-4 py-3 bg-gray-50 border-t border-gray-100">
                {{ $completedFollowUps->appends(request()->except('completed_page'))->links() }}
            </div>
        </div>
    </div>

    <script>
        const searchInput = document.getElementById('searchInput');
        const searchForm = document.getElementById('searchForm');
        let timeout = null;
        searchInput.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => searchForm.submit(), 500);
        });
    </script>
</x-app-layout>