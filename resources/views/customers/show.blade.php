@section('title', 'Customers')
<x-app-layout>
    <div class="max-w-4xl mx-auto lg:mt-8">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6 px-2">
            <div class="flex items-center">
                <a href="{{ route('customers.index') }}" class="text-gray-400 hover:text-gray-600 transition mr-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-gray-800 tracking-tight">{{ $customer->first_name }} {{ $customer->last_name }}</h1>
                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Customer Profile • ID #{{ $customer->id }}</p>
                </div>
            </div>
            
            <div class="flex items-center">
                @php
                    $statusClasses = [
                        'Active'   => 'bg-green-50 text-green-700 border-green-200',
                        'Inactive' => 'bg-red-50 text-red-700 border-red-200',
                        'Lead'     => 'bg-blue-50 text-blue-700 border-blue-200'
                    ];
                    $currentClass = $statusClasses[$customer->status] ?? 'bg-gray-50 text-gray-700 border-gray-200';
                @endphp
                <span class="px-3 py-1 border rounded-full text-[10px] font-bold uppercase tracking-widest {{ $currentClass }}">
                    {{ $customer->status }}
                </span>
            </div>
        </div>

        <div class="space-y-6 mb-10">
            <div class="bg-white shadow-sm border border-gray-200 rounded-sm overflow-hidden">
                <div class="bg-gray-50/50 border-b border-gray-100 px-4 py-3">
                    <h2 class="text-[11px] uppercase font-bold text-teal-700 tracking-wider">General Information</h2>
                </div>
                
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
                    <div>
                        <label class="block text-[11px] uppercase font-bold text-gray-400 mb-1">Email Address</label>
                        <p class="text-sm text-blue-600 font-medium break-all">{{ $customer->email }}</p>
                    </div>

                    <div>
                        <label class="block text-[11px] uppercase font-bold text-gray-400 mb-1">Contact Number</label>
                        <p class="text-sm text-gray-700 font-medium">{{ $customer->phone ?? 'Not provided' }}</p>
                    </div>

                    <div>
                        <label class="block text-[11px] uppercase font-bold text-gray-400 mb-1">Company / Organization</label>
                        <p class="text-sm text-gray-700 font-medium">{{ $customer->company_name ?? 'Individual' }}</p>
                    </div>

                    <div>
                        <label class="block text-[11px] uppercase font-bold text-gray-400 mb-1">Office Address</label>
                        <p class="text-sm text-gray-700 font-medium">{{ $customer->address ?? 'No address recorded' }}</p>
                    </div>
                </div>
            </div>

            @if(in_array(auth()->user()->role, ['admin', 'manager']))
            <div class="bg-white shadow-sm border border-gray-200 rounded-sm overflow-hidden">
                <div class="bg-gray-50/50 border-b border-gray-100 px-4 py-3">
                    <h2 class="text-[11px] uppercase font-bold text-teal-700 tracking-wider">Internal Assignment Details</h2>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
                    <div>
                        <label class="block text-[11px] uppercase font-bold text-gray-400 mb-1">Assigned Staff Name</label>
                        <p class="text-sm text-gray-700 font-medium">{{ $customer->user->name ?? 'Unassigned' }}</p>
                    </div>
                    <div>
                        <label class="block text-[11px] uppercase font-bold text-gray-400 mb-1">Staff User ID</label>
                        <p class="text-sm text-gray-500 font-mono">#{{ $customer->assigned_user_id ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
            @endif

            <div class="mt-8">
                <div class="flex items-center justify-between mb-4 px-2">
                    <h3 class="text-[11px] font-bold uppercase tracking-widest text-teal-700">Recent Activities</h3>
                    <a href="{{ route('activities.create', ['type' => 'customer', 'id' => $customer->id]) }}" 
                       class="text-[10px] font-bold text-teal-600 hover:text-teal-800 uppercase tracking-widest">
                        + Log New
                    </a>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100">
                            <tbody class="divide-y divide-gray-50">
                                @forelse($activities as $activity)
                                    <tr class="hover:bg-gray-50/50 transition">
                                        <td class="px-4 sm:px-6 py-4">
                                            <div class="flex flex-wrap items-center gap-3 mb-1">
                                                <span class="px-2 py-0.5 text-[9px] font-bold uppercase rounded-full 
                                                    @if($activity->activity_type == 'call') bg-green-100 text-green-700
                                                    @elseif($activity->activity_type == 'email') bg-blue-100 text-blue-700
                                                    @elseif($activity->activity_type == 'meeting') bg-purple-100 text-purple-700
                                                    @else bg-gray-100 text-gray-700 @endif">
                                                    {{ $activity->activity_type }}
                                                </span>
                                                <span class="text-[10px] text-gray-400 font-medium tracking-wider">
                                                    {{ $activity->activity_date }}
                                                </span>
                                            </div>
                                            <p class="text-sm font-bold text-gray-800">{{ $activity->title ?? 'Activity Logged' }}</p>
                                            <p class="text-xs text-gray-500 italic mt-1">{{ $activity->description }}</p>
                                            <div class="mt-2">
                                                <span class="text-[10px] text-teal-600 font-bold uppercase">Staff: {{ $activity->user->name ?? 'Unknown' }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td class="px-6 py-8 text-center text-xs text-gray-400 italic">No activities recorded.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mt-10">
                <div class="flex items-center justify-between mb-4 px-2">
                    <h3 class="text-sm font-bold uppercase tracking-widest text-teal-700">Recent Follow-ups</h3>
                    <a href="{{ route('follow-ups.create', ['type' => 'customer', 'id' => $customer->id]) }}" 
                       class="text-[10px] font-bold text-teal-600 hover:text-teal-800 uppercase">
                        + Schedule New
                    </a>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100">
                            <tbody class="divide-y divide-gray-50">
                                @forelse($followUps as $f)
                                    @php $isOverdue = $f->status === 'pending' && \Carbon\Carbon::parse($f->due_date)->isPast(); @endphp
                                    <tr class="hover:bg-gray-50/50 transition">
                                        <td class="px-4 sm:px-6 py-4">
                                            <div class="flex items-center gap-3 mb-1">
                                                <span class="text-[10px] font-bold uppercase {{ $isOverdue ? 'text-red-500' : 'text-gray-400' }}">
                                                    Due: {{ $f->due_date }} {{ $isOverdue ? '(Overdue)' : '' }}
                                                </span>
                                            </div>
                                            <p class="text-sm font-bold text-gray-800">{{ $f->title }}</p>
                                            <p class="text-xs text-gray-500 italic line-clamp-1">{{ $f->description }}</p>
                                        </td>
                                        <td class="px-4 sm:px-6 py-4 text-right">
                                            @if($f->status === 'pending')
                                                <form method="POST" action="{{ route('follow-ups.complete', $f->id) }}">
                                                    @csrf
                                                    <button class="text-[10px] font-bold text-green-600 uppercase hover:underline">Done</button>
                                                </form>
                                            @else
                                                <span class="text-[10px] font-bold text-green-600 uppercase">Completed</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td class="px-6 py-6 text-center text-xs text-gray-400 italic">No follow-ups found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="mt-8 bg-white p-6 rounded shadow-sm border border-gray-100">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-6">
                    <span>Created: {{ $customer->created_at->format('M d, Y H:i') }}</span>
                    <span>Last Activity: {{ $customer->updated_at ? $customer->updated_at->diffForHumans() : 'No activity yet' }}</span>
                </div>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ url()->previous() }}" class="text-sm font-semibold text-gray-400 hover:text-gray-600 transition text-center sm:mr-4">
                        Back to List
                    </a>
                    @if($customer->trashed())
                        <button disabled class="bg-gray-100 text-gray-400 px-8 py-2.5 rounded-md text-sm font-bold cursor-not-allowed border border-gray-200">
                            Customer is Trashed
                        </button>
                    @else
                        <a href="{{ route('customers.edit', $customer) }}" class="bg-teal-600 hover:bg-teal-700 text-white px-8 py-2.5 rounded-md text-sm font-bold transition shadow-md text-center">
                            Edit Customer
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>