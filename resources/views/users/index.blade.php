@section('title', 'Users')
<x-app-layout>
    <div class="max-w-7xl mx-auto py-6 sm:py-8 px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">User Management</h1>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Manage system access & roles</p>
            </div>

            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full md:w-auto">
                <form method="GET" action="{{ route('users.index') }}" class="flex-1 sm:flex-none">
                    <select name="role" onchange="this.form.submit()" class="w-full text-sm border-gray-200 rounded-md focus:border-teal-500 focus:ring-teal-500 py-2">
                        <option value="">All Roles</option>
                        @foreach(['admin' => 'Admin', 'manager' => 'Manager', 'sales_staff' => 'Staff'] as $val => $label)
                            <option value="{{ $val }}" {{ request('role') == $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </form>
                
                <a href="{{ route('users.create') }}" class="bg-teal-600 text-white px-6 py-2 rounded-md text-[10px] font-bold uppercase hover:bg-teal-700 text-center shadow-sm transition">
                    + Create User
                </a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left min-w-[600px]">
                    <thead class="bg-gray-50 text-[10px] uppercase text-gray-400 tracking-wider">
                        <tr>
                            <th class="px-6 py-4">ID</th>
                            <th class="px-6 py-4">User</th>
                            <th class="px-6 py-4">Role</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($users as $u)
                            <tr class="hover:bg-gray-50/50 group transition-colors duration-200">
                                <td class="px-6 py-4 text-xs font-mono text-gray-400">#{{ $u->id }}</td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-800">{{ $u->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $u->email }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 text-[9px] font-bold uppercase rounded-full 
                                        {{ $u->role == 'admin' ? 'bg-red-50 text-red-600 border border-red-100' : 
                                           ($u->role == 'manager' ? 'bg-indigo-50 text-indigo-600 border border-indigo-100' : 
                                           'bg-gray-50 text-gray-600 border border-gray-200') }}">
                                        {{ str_replace('_', ' ', $u->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-4">
                                        <a href="{{ route('users.edit', $u->id) }}" 
                                           class="text-gray-300 hover:text-teal-600 transition-colors duration-200" 
                                           title="Edit User">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        
                                        @if(auth()->id() !== $u->id)
                                        <form method="POST" action="{{ route('users.destroy', $u->id) }}" onsubmit="return confirm('Permanently delete this user account?')">
                                            @csrf @method('DELETE')
                                            <button class="text-gray-300 hover:text-red-600 transition-colors duration-200" title="Delete User">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>