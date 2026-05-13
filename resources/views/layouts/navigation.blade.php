<div x-data="{ open: false }">
    <div class="lg:hidden flex items-center justify-between bg-gray-900 text-white p-4 sticky top-0 z-50">
        <div class="flex items-center gap-2">
            <img src="{{ asset('images/icon.png') }}" class="h-6 w-auto" alt="Icon">
            <span class="font-bold">NullCRM</span>
        </div>
        <button @click="open = !open" class="p-2 text-gray-400 hover:text-white">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <aside 
        :class="open ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-40 w-64 bg-gray-900 text-white flex flex-col shadow-xl transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0 lg:h-screen">
        
        <div class="p-5 border-b border-gray-700">
            <div class="hidden lg:flex items-center gap-3 mb-4">
                <img src="{{ asset('images/icon.png') }}" alt="NullCRM Icon" class="h-7 w-auto">
                <h2 class="text-xl font-bold">NullCRM System</h2>
            </div>

            @auth
                <p class="text-sm">
                    {{ Auth::user()->name }} <br>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ Auth::user()->role }}</span>
                </p>
            @endauth
        </div>

        <nav class="flex-grow overflow-y-auto p-5 space-y-2 scroller-style">
            <a href="{{ route('dashboard') }}" 
                class="block p-2 rounded transition {{ request()->routeIs('dashboard') ? 'bg-teal-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                Dashboard
            </a>

            <a href="{{ route('customers.index') }}" 
                class="block p-2 rounded transition {{ request()->routeIs('customers.*') ? 'bg-teal-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                Customers
            </a>

            <a href="{{ route('leads.index') }}" 
                class="block p-2 rounded transition {{ request()->routeIs('leads.*') ? 'bg-teal-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                Leads
            </a>

            <a href="{{ route('activities.index') }}" 
                class="block p-2 rounded transition {{ request()->routeIs('activities.*') ? 'bg-teal-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                Activities
            </a>

            <a href="{{ route('follow-ups.index') }}" 
                class="block p-2 rounded transition {{ request()->routeIs('follow-ups.*') ? 'bg-teal-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                Follow-ups
            </a>
            
            @if(in_array(Auth::user()->role, ['admin', 'manager']))
                <a href="{{ route('reports.index') }}" 
                    class="block p-2 rounded transition {{ request()->routeIs('reports.*') ? 'bg-teal-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                    Reports
                </a>
            @endif

            @if(Auth::user()->role === 'admin')
                <a href="{{ route('trash.index') }}" 
                    class="block p-2 rounded transition {{ request()->routeIs('trash.*') ? 'bg-teal-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                    System Archive
                </a>
            @endif
        </nav>

        <div class="p-5 border-t border-gray-700 mt-auto space-y-4">
            @if(Auth::user()->role === 'admin')
                <a href="{{ route('users.index') }}" 
                    class="block p-2 rounded transition {{ request()->routeIs('users.*') ? 'bg-teal-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                    Users
                </a>
            @else
                <a href="{{ route('users.edit', Auth::id()) }}" 
                    class="block p-2 rounded transition {{ request()->routeIs('users.edit') ? 'bg-teal-600 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                    Profile
                </a>
            @endif

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full text-left hover:bg-red-600 p-2 rounded text-gray-300 transition">
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <div x-show="open" @click="open = false" class="fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden"></div>
</div>

<style>
    .scroller-style::-webkit-scrollbar {
        width: 6px;
    }
    .scroller-style::-webkit-scrollbar-track {
        background: transparent;
    }
    .scroller-style::-webkit-scrollbar-thumb {
        background-color: rgb(31, 41, 55);
        border-radius: 99px;
    }
    .scroller-style::-webkit-scrollbar-thumb:hover {
        background-color: rgb(75, 85, 99);
    }
</style>