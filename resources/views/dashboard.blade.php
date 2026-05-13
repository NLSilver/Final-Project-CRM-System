@section('title', 'Dashboard')
<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="mb-10">
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Dashboard Overview</h1>
            <p class="mt-2 text-sm text-gray-500 font-medium">
                Welcome back. Here is what's happening with your <span class="text-teal-600 font-bold">NullCRM</span> projects today.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-6 mb-10 sm:grid-cols-2 lg:grid-cols-3">
            <a href="{{ route('customers.index') }}" class="relative group bg-white pt-5 px-6 pb-8 shadow-sm rounded-xl border border-gray-100 hover:border-teal-200 hover:shadow-md transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                </div>
                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Total Customers</p>
                <p class="mt-1 text-4xl font-bold text-gray-900">{{ $customersCount }}</p>
            </a>

            <a href="{{ route('leads.index') }}" class="relative group bg-white pt-5 px-6 pb-8 shadow-sm rounded-xl border border-gray-100 hover:border-teal-200 hover:shadow-md transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-2 bg-teal-50 rounded-lg text-teal-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </div>
                </div>
                <p class="text-sm font-semibold text-teal-600 uppercase tracking-wider">Active Leads</p>
                <p class="mt-1 text-4xl font-bold text-gray-900">{{ $activeLeadsCount }}</p>
            </a>

            <a href="{{ route('follow-ups.index') }}" class="relative group bg-white pt-5 px-6 pb-8 shadow-sm rounded-xl border border-gray-100 hover:border-green-200 hover:shadow-md transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-2 bg-green-50 rounded-lg text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <p class="text-sm font-semibold text-green-600 uppercase tracking-wider">Completed Tasks</p>
                <p class="mt-1 text-4xl font-bold text-gray-900">{{ $completedFollowUpsCount }}</p>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col">
                <div class="p-6 border-b border-gray-50 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-800">Recent Activites</h2>
                    <span class="text-xs font-bold text-gray-400 uppercase">Live Feed</span>
                </div>
                <div class="flex-grow">
                    @forelse($recentActivities as $act)
                        <div class="px-6 py-4 hover:bg-gray-50/50 transition border-b border-gray-50 last:border-0">
                            <div class="flex items-start gap-4">
                                <div class="mt-1 w-2 h-2 rounded-full {{ $act->customer_id ? 'bg-blue-400' : 'bg-teal-400' }}"></div>
                                <div class="flex-grow">
                                    <div class="flex justify-between items-center mb-1">
                                        <p class="text-xs font-bold text-gray-900 uppercase">
                                            {{ $act->customer ? $act->customer->first_name . ' ' . $act->customer->last_name : $act->lead->name }}
                                        </p>
                                        <span class="text-[10px] font-medium text-gray-400">{{ $act->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-sm text-gray-600 leading-relaxed">{{ $act->description }}</p>
                                    <div class="mt-2 flex items-center text-[10px] text-gray-400 font-bold uppercase">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/></svg>
                                        {{ $act->user->name }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center text-gray-400">
                            <p class="text-sm italic">No recent activities recorded.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col">
                <div class="p-6 border-b border-gray-50 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-800">Pending Actions</h2>
                    <a href="{{ route('follow-ups.index') }}" class="text-xs font-bold text-teal-600 hover:text-teal-800 transition">View All</a>
                </div>
                <div class="flex-grow">
                    @forelse($upcomingFollowUps as $f)
                        <div class="px-6 py-5 hover:bg-gray-50/50 transition border-b border-gray-50 last:border-0">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-sm font-bold text-gray-900 mb-1">{{ $f->title }}</h3>
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="text-[10px] font-bold uppercase {{ $f->customer_id ? 'text-blue-500' : 'text-teal-500' }}">
                                            {{ $f->customer ? $f->customer->first_name : $f->lead->name }}
                                        </span>
                                        <span class="text-gray-300">•</span>
                                        <span class="text-[10px] text-gray-500 font-medium uppercase tracking-tighter">By {{ $f->user->name }}</span>
                                    </div>
                                    <p class="text-xs text-gray-500 line-clamp-1 italic">{{ $f->description }}</p>
                                </div>
                                <div class="text-right">
                                    <div class="inline-flex flex-col items-center px-3 py-1 bg-amber-50 rounded-lg border border-amber-100">
                                        <span class="text-[10px] font-black text-amber-700 uppercase">{{ \Carbon\Carbon::parse($f->due_date)->format('M') }}</span>
                                        <span class="text-lg font-bold text-amber-800 leading-none">{{ \Carbon\Carbon::parse($f->due_date)->format('d') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center text-gray-400">
                            <p class="text-sm italic">You're all caught up!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>