@section('title', 'Customers')
<x-app-layout>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6 bg-white p-4 rounded-lg shadow-sm border border-gray-100">
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 w-full md:w-auto">
            <a href="{{ route('customers.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-md text-sm font-bold transition shadow-sm text-center">
                + Add Customer
            </a>
            
            <form action="{{ route('customers.index') }}" method="GET" id="searchForm" class="relative group w-full sm:w-64">
                <input type="text" 
                       name="search" 
                       id="searchInput" 
                       value="{{ $search ?? '' }}" 
                       placeholder="Search customers..." 
                       autocomplete="off" 
                       class="pl-8 pr-4 py-1.5 border-none focus:ring-0 text-sm text-gray-600 placeholder-gray-400 w-full bg-transparent border-b border-transparent focus:border-gray-200">
                <span class="absolute left-0 top-1.5 text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
            </form>
        </div>

        <div class="flex space-x-4 text-sm w-full md:w-auto justify-start md:justify-end">
            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full font-medium">Active: {{ $activeCount }}</span>
            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full font-medium">Inactive: {{ $inactiveCount }}</span>
        </div>
    </div>

    @php
        $sections = [
            ['data' => $activeCustomers, 'label' => 'Active', 'color' => 'green', 'param' => 'active_page'],
            ['data' => $inactiveCustomers, 'label' => 'Inactive', 'color' => 'red', 'param' => 'inactive_page']
        ];
    @endphp

    @foreach($sections as $section)
    <div class="mb-12">
        <div class="flex items-center mb-3 px-2">
            <span class="text-{{ $section['color'] }}-600 mr-2 text-xs">▼</span>
            <h2 class="text-{{ $section['color'] }}-700 font-bold text-xs uppercase tracking-widest">{{ $section['label'] }} Contacts</h2>
        </div>

        <div class="bg-white shadow-sm border border-gray-200 rounded-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="text-gray-400 text-[11px] uppercase border-b border-gray-100 bg-gray-50/50">
                            <th class="whitespace-nowrap px-4 py-3 text-left font-semibold border-r border-gray-100">Name</th>
                            <th class="whitespace-nowrap px-4 py-3 text-left font-semibold border-r border-gray-100">Email</th>
                            <th class="whitespace-nowrap px-4 py-3 text-left font-semibold border-r border-gray-100">Company</th>
                            @if(auth()->user()->role !== 'sales_staff')
                                <th class="whitespace-nowrap px-4 py-3 text-left font-semibold border-r border-gray-100">Assigned User</th>
                            @endif
                            <th class="whitespace-nowrap px-4 py-3 text-center font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($section['data'] as $customer)
                            <tr class="hover:bg-{{ $section['color'] }}-50/20 transition group">
                                <td class="px-4 py-3 text-sm font-medium text-gray-700 border-r border-gray-100 border-l-4 border-l-{{ $section['color'] }}-500">
                                    {{ $customer->first_name }} {{ $customer->last_name }}
                                </td>
                                <td class="px-4 py-3 text-sm text-blue-600 border-r border-gray-100">{{ $customer->email }}</td>
                                <td class="px-4 py-3 border-r border-gray-100">
                                    <span class="bg-gray-100 text-gray-700 text-[10px] px-2 py-1 rounded uppercase font-bold">{{ $customer->company_name ?? 'Individual' }}</span>
                                </td>
                                @if(auth()->user()->role !== 'sales_staff')
                                    <td class="px-4 py-3 border-r border-gray-100 text-sm text-gray-700">
                                        {{ $customer->user->name ?? 'Unassigned' }}
                                    </td>
                                @endif
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center gap-3">
                                        <a href="{{ route('customers.show', $customer) }}" class="group/btn" title="View Profile">
                                            <svg class="w-4 h-4 text-gray-400 group-hover/btn:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>

                                        <a href="{{ route('customers.edit', $customer) }}" class="group/btn" title="Edit Customer">
                                            <svg class="w-4 h-4 text-gray-400 group-hover/btn:text-teal-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>

                                        @if(auth()->user()->role === 'admin')
                                        <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline" onsubmit="return confirm('Archive this customer?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="group/btn" title="Delete">
                                                <svg class="w-4 h-4 text-gray-400 group-hover/btn:text-red-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()->role === 'sales_staff' ? '5' : '6' }}" class="px-4 py-8 text-center text-gray-400 italic text-sm border-l-4 border-l-gray-100">
                                    No {{ strtolower($section['label']) }} contacts found on this page.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 bg-gray-50 border-t border-gray-100">
                {{ $section['data']->appends(request()->except($section['param']))->links() }}
            </div>
        </div>
    </div>
    @endforeach

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
    </script>
</x-app-layout>