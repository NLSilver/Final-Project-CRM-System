@section('title', 'Activities')
<x-app-layout>
    <div class="max-w-5xl mx-auto lg:py-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 px-2">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Activity Timeline</h1>
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Historical Log of Client Interactions</p>
            </div>
            <a href="{{ route('activities.create') }}" class="w-full sm:w-auto bg-teal-600 hover:bg-teal-700 text-white px-6 py-2 rounded-md text-sm font-bold transition shadow-sm text-center">
                + Log New Activity
            </a>
        </div>

        <form action="{{ route('activities.index') }}" method="GET" id="autoSubmitForm" class="bg-white p-4 sm:p-6 rounded-lg border border-gray-100 shadow-sm mb-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                <div class="sm:col-span-2 lg:col-span-1">
                    <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Client name..." class="w-full text-sm border-gray-200 rounded-md focus:ring-teal-500">
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">From</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full text-sm border-gray-200 rounded-md focus:ring-teal-500">
                </div>
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">To</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full text-sm border-gray-200 rounded-md focus:ring-teal-500">
                </div>
                
                @if(in_array(auth()->user()->role, ['admin', 'manager']))
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase mb-1 block">Staff</label>
                    <select name="user_id" class="w-full text-sm border-gray-200 rounded-md focus:ring-teal-500">
                        <option value="">All Staff</option>
                        @foreach($users as $role => $roleUsers)
                            <optgroup label="{{ ucfirst(str_replace('_', ' ', $role)) }}">
                                @foreach($roleUsers as $u)
                                    <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
                @endif

                <div class="flex items-center justify-end gap-3 lg:pb-2">
                    @if(request()->anyFilled(['search', 'start_date', 'end_date', 'user_id']))
                        <a href="{{ route('activities.index') }}" class="text-xs font-bold text-red-400 uppercase hover:underline">Clear</a>
                    @endif
                </div>
            </div>
        </form>

        <div class="space-y-8 pb-10">
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                <h2 class="px-4 py-3 bg-gray-50 text-[10px] font-bold uppercase text-blue-700 border-b border-blue-100 tracking-widest">
                    Customer Interactions
                </h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <tbody class="divide-y divide-gray-50">
                            @forelse($customerActivities as $activity)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-4 sm:px-6 py-4">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="px-2 py-0.5 text-[9px] font-bold uppercase rounded-full 
                                                @if($activity->activity_type == 'call') bg-green-100 text-green-700
                                                @elseif($activity->activity_type == 'email') bg-blue-100 text-blue-700
                                                @elseif($activity->activity_type == 'meeting') bg-purple-100 text-purple-700
                                                @else bg-gray-100 text-gray-700 @endif">
                                                {{ $activity->activity_type }}
                                            </span>
                                            <span class="text-[10px] text-gray-400 font-medium">{{ $activity->activity_date }}</span>
                                        </div>
                                        <p class="text-sm font-bold text-gray-800 mb-1">
                                            {{ $activity->customer->first_name . ' ' . $activity->customer->last_name }}
                                        </p>
                                        <p class="text-xs text-gray-500 italic mb-2 line-clamp-2">{{ $activity->description }}</p>
                                        <div class="flex items-center text-[10px] text-blue-600 font-bold uppercase">
                                            Logged by {{ $activity->user->name ?? 'Unknown' }}
                                        </div>
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 text-right">
                                        <form action="{{ route('activities.destroy', $activity) }}" method="POST" onsubmit="return confirm('Delete log?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-red-600 transition">
                                                <svg class="w-5 h-5 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td class="px-6 py-10 text-center text-xs text-gray-400 italic">No customer interactions on this page.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-4 py-3 bg-gray-50 border-t border-gray-100">
                    {{ $customerActivities->appends(request()->except('customer_page'))->links() }}
                </div>
            </div>

            <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                <h2 class="px-4 py-3 bg-gray-50 text-[10px] font-bold uppercase text-teal-700 border-b border-teal-100 tracking-widest">
                    Lead Interactions
                </h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <tbody class="divide-y divide-gray-50">
                            @forelse($leadActivities as $activity)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-4 sm:px-6 py-4">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="px-2 py-0.5 text-[9px] font-bold uppercase rounded-full 
                                                @if($activity->activity_type == 'call') bg-green-100 text-green-700
                                                @elseif($activity->activity_type == 'email') bg-blue-100 text-blue-700
                                                @elseif($activity->activity_type == 'meeting') bg-purple-100 text-purple-700
                                                @else bg-gray-100 text-gray-700 @endif">
                                                {{ $activity->activity_type }}
                                            </span>
                                            <span class="text-[10px] text-gray-400 font-medium">{{ $activity->activity_date }}</span>
                                        </div>
                                        <p class="text-sm font-bold text-gray-800 mb-1">{{ $activity->lead->name ?? 'N/A' }}</p>
                                        <p class="text-xs text-gray-500 italic mb-2 line-clamp-2">{{ $activity->description }}</p>
                                        <div class="flex items-center text-[10px] text-teal-600 font-bold uppercase">
                                            Logged by {{ $activity->user->name ?? 'Unknown' }}
                                        </div>
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 text-right">
                                        <form action="{{ route('activities.destroy', $activity) }}" method="POST" onsubmit="return confirm('Delete log?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-red-600 transition">
                                                <svg class="w-5 h-5 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td class="px-6 py-10 text-center text-xs text-gray-400 italic">No lead interactions on this page.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-4 py-3 bg-gray-50 border-t border-gray-100">
                    {{ $leadActivities->appends(request()->except('lead_page'))->links() }}
                </div>
            </div>
        </div>
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