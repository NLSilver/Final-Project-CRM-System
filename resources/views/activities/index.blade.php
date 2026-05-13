@section('title', 'Activites')
<x-app-layout>
    <div class="max-w-5xl mx-auto py-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Activity Timeline</h1>

        <form action="{{ route('activities.index') }}" method="GET" id="autoSubmitForm" class="bg-white p-4 rounded-lg border border-gray-100 shadow-sm mb-8 grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
            <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Name..." class="w-full text-sm border-gray-200 rounded-md">
            </div>
            <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1">From</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full text-sm border-gray-200 rounded-md">
            </div>
            <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1">To</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full text-sm border-gray-200 rounded-md">
            </div>
            @if(in_array(auth()->user()->role, ['admin', 'manager']))
            <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1">Staff</label>
                <select name="user_id" class="w-full text-sm border-gray-200 rounded-md">
                    <option value="">All Staff</option>
                    @foreach($users as $role => $roleUsers)
                        <optgroup label="{{ ucfirst(str_replace('_', ' ', $role)) }}">
                            @foreach($roleUsers as $u)<option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>@endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>
            @else <div></div> @endif

            <div class="flex items-center justify-end gap-3 pb-1">
                @if(request()->anyFilled(['search', 'start_date', 'end_date', 'user_id']))
                    <a href="{{ route('activities.index') }}" class="text-xs font-bold text-red-400 uppercase hover:underline">Clear</a>
                @endif
            </div>
        </form>

        <div class="bg-white rounded-lg border mb-8 overflow-hidden">
            <h2 class="px-4 py-3 bg-gray-50 text-xs font-bold uppercase text-blue-700 border-b border-blue-100">
                Customer Activities
            </h2>
            <table class="min-w-full divide-y divide-gray-100">
                <tbody class="divide-y divide-gray-50">
                    @forelse($customerActivities as $activity)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="px-2 py-0.5 text-[9px] font-bold uppercase rounded-full bg-gray-100 text-gray-600">{{ $activity->activity_type }}</span>
                                        <span class="text-[10px] text-gray-400">{{ $activity->activity_date }}</span>
                                    </div>
                                    <p class="text-sm font-bold text-gray-800">
                                        {{ $activity->customer->first_name . ' ' . $activity->customer->last_name }}
                                    </p>
                                    <span class="text-[10px] text-blue-600 font-bold uppercase">Staff: {{ $activity->user->name ?? 'Unknown' }}, Description: {{ $activity->description }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <form action="{{ route('activities.destroy', $activity) }}" method="POST" class="flex justify-end items-center" onsubmit="return confirm('Delete this activity?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="group">
                                        <svg class="w-5 h-5 text-gray-400 group-hover:text-red-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td class="px-6 py-4 text-center text-gray-400 italic">No customer records.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bg-white rounded-lg border mb-8 overflow-hidden">
            <h2 class="px-4 py-3 bg-gray-50 text-xs font-bold uppercase text-teal-700 border-b border-teal-100">
                Lead Activities
            </h2>
            <table class="min-w-full divide-y divide-gray-100">
                <tbody class="divide-y divide-gray-50">
                    @forelse($leadActivities as $activity)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="px-2 py-0.5 text-[9px] font-bold uppercase rounded-full bg-gray-100 text-gray-600">{{ $activity->activity_type }}</span>
                                        <span class="text-[10px] text-gray-400">{{ $activity->activity_date }}</span>
                                    </div>
                                    <p class="text-sm font-bold text-gray-800">{{ $activity->lead->name ?? 'N/A' }}</p>
                                    <span class="text-[10px] text-teal-600 font-bold uppercase">Staff: {{ $activity->user->name ?? 'Unknown' }}, Description: {{ $activity->description }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <form action="{{ route('activities.destroy', $activity) }}" method="POST" class="flex justify-end items-center" onsubmit="return confirm('Delete?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="group">
                                        <svg class="w-5 h-5 text-gray-400 group-hover:text-red-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td class="px-6 py-4 text-center text-gray-400 italic">No lead records.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    <script>
        const form = document.getElementById('autoSubmitForm');
        form.addEventListener('change', () => form.submit());
        let timeout = null;
        form.querySelector('input[name="search"]').addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => form.submit(), 800);
        });
    </script>
</x-app-layout>