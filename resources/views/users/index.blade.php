@section('title', 'Users')
<x-app-layout>
    <div class="max-w-7xl mx-auto py-8">
        <div class="flex justify-between items-end mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">User Management</h1>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Manage system access & roles</p>
            </div>

            <div class="flex justify-between items-center mb-6">
                <form method="GET" action="{{ route('users.index') }}" class="flex items-center gap-2">
                    <select name="role" onchange="this.form.submit()" class="text-sm border-gray-200 rounded-md focus:border-teal-500 focus:ring-teal-500">
                        <option value="">All Roles</option>
                        @foreach(['admin', 'manager', 'staff'] as $role)
                            <option value="{{ $role }}" {{ request('role') == $role ? 'selected' : '' }}>
                                {{ ucfirst($role) }}
                            </option>
                        @endforeach
                    </select>
                </form>
                
                <a href="{{ route('users.create') }}" class="bg-teal-600 text-white px-4 py-2 rounded-md text-[10px] font-bold uppercase hover:bg-teal-700">
                    + Create User
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-[10px] uppercase text-gray-400">
                    <tr>
                        <th class="px-6 py-3">ID</th>
                        <th class="px-6 py-3">User</th>
                        <th class="px-6 py-3">Role</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($users as $u)
                        <tr class="hover:bg-gray-50 group transition-colors duration-200">
                            <td class="px-6 py-4 text-xs font-mono text-gray-400">#{{ $u->id }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800">{{ $u->name }}</div>
                                <div class="text-xs text-gray-500">{{ $u->email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-[9px] font-bold uppercase rounded-full 
                                    {{ $u->role == 'admin' ? 'bg-red-100 text-red-600' : ($u->role == 'manager' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-600') }}">
                                    {{ $u->role }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-4">
                                    
                                    <a href="{{ route('users.edit', $u->id) }}" 
                                    class="text-gray-300 group-hover:text-teal-600 transition-colors duration-200" 
                                    title="Edit User">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    
                                    <form method="POST" action="{{ route('users.destroy', $u->id) }}" onsubmit="return confirm('Are you sure?')">
                                        @csrf @method('DELETE')
                                        <button class="text-gray-300 group-hover:text-red-600 transition-colors duration-200" title="Delete User">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>