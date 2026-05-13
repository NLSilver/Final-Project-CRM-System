@section('title', 'System Archive')
<x-app-layout>
    <div class="max-w-7xl mx-auto py-6 sm:py-10 px-4 sm:px-6 lg:px-8">
        <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">System Archive</h1>
                <p class="text-sm text-gray-500">Manage soft-deleted records and data recovery.</p>
            </div>
            <a href="{{ route('customers.index') }}" class="w-full sm:w-auto px-4 py-2 bg-white border border-gray-200 text-xs font-bold text-gray-600 rounded-lg hover:bg-gray-50 transition shadow-sm flex items-center justify-center">
                <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                EXIT ARCHIVE
            </a>
        </div>

        <div class="flex flex-wrap gap-1 mb-6 bg-gray-100 p-1 rounded-xl w-fit">
            @foreach($tabs as $key => $tab)
                <a href="{{ route('trash.index', ['type' => $key]) }}" 
                   class="px-4 sm:px-6 py-2 text-[10px] sm:text-xs font-bold uppercase tracking-widest rounded-lg transition {{ $activeType === $key ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                    {{ $tab['label'] }}
                </a>
            @endforeach
        </div>

        <div class="bg-white shadow-sm border border-gray-200 rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50/50">
                        <tr class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                            <th class="px-6 py-4 text-left whitespace-nowrap">Record Information</th>
                            <th class="px-6 py-4 text-left whitespace-nowrap">Deletion Metadata</th>
                            <th class="px-6 py-4 text-center whitespace-nowrap">Recovery Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($items as $item)
                            <tr class="hover:bg-blue-50/20 transition-colors duration-200 group text-gray-400">
                                <td class="px-6 py-4 border-r border-gray-100 min-w-[200px]">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 w-2 h-2 rounded-full mr-3 bg-{{ $tabs[$activeType]['color'] }}-500 shadow-sm transition-transform group-hover:scale-125"></div>
                                        <div class="truncate">
                                            @if($activeType === 'activities')
                                                <p class="text-sm font-bold text-gray-800 truncate">
                                                    {{ $item->customer->full_name ?? $item->lead->full_name ?? 'Unknown Contact' }}
                                                </p>
                                                <p class="text-[10px] font-medium uppercase tracking-widest text-blue-500">
                                                    {{ $item->activity_type }}
                                                </p>
                                            @elseif($activeType === 'follow-ups')
                                                <p class="text-sm font-bold text-gray-800 truncate">
                                                    {{ $item->customer->full_name ?? $item->lead->full_name ?? 'Unknown Contact' }}
                                                </p>
                                                <p class="text-[10px] font-medium italic text-gray-400 truncate">
                                                    {{ $item->title }}
                                                </p>
                                            @else
                                                <p class="text-sm font-bold text-gray-800 truncate">
                                                    {{ $item->name ?? $item->first_name . ' ' . $item->last_name }}
                                                </p>
                                                <p class="text-[10px] font-medium italic tracking-tight truncate">
                                                    {{ $item->email ?? $item->company_name ?? 'No additional info' }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 border-r border-gray-100 whitespace-nowrap">
                                    <p class="text-xs font-medium group-hover:text-blue-600 transition-colors">Deleted {{ $item->deleted_at->diffForHumans() }}</p>
                                    <p class="text-[10px] uppercase font-bold tracking-tight">{{ $item->deleted_at->format('M d, Y • h:i A') }}</p>
                                </td>

                                <td class="px-6 py-4 text-sm text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        @if($activeType === 'customers' || $activeType === 'leads')
                                        <a href="{{ route($activeType . '.show', $item->id) }}" class="p-1 group-hover:text-blue-600 transition-colors duration-200" title="View">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        <span class="text-gray-200 group-hover:text-gray-300 transition-colors">|</span>
                                        @endif

                                        <form action="{{ route('trash.restore', $item->id) }}" method="POST" class="flex items-center">
                                            @csrf
                                            <input type="hidden" name="type" value="{{ $activeType }}">
                                            <button type="submit" class="p-1 group-hover:text-teal-600 transition-colors duration-200" title="Restore">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                </svg>
                                            </button>
                                        </form>

                                        <span class="text-gray-200 group-hover:text-gray-300 transition-colors">|</span>
                                        <form action="{{ route('trash.forceDelete', $item->id) }}" method="POST" class="flex items-center">
                                            @csrf @method('DELETE')
                                            <input type="hidden" name="type" value="{{ $activeType }}">
                                            <button type="submit" class="p-1 group-hover:text-red-500 transition-colors duration-200" onclick="return confirm('Permanently purge this record?')" title="Delete Permanently">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-10 text-center text-gray-400 italic text-sm">
                                    No archived {{ $activeType }} found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>