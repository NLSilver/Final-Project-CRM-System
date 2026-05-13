@section('title', 'Users')
<x-app-layout>
    <div class="max-w-3xl mx-auto py-10">
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-8">
            <h2 class="text-sm font-bold uppercase text-gray-700 mb-6 border-b pb-4">Edit User: {{ $user->name }}</h2>
            
            <form action="{{ route('users.update', $user->id) }}" method="POST" class="space-y-6">
                @csrf @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full mt-1 border-gray-200 rounded-md shadow-sm focus:border-teal-500 text-sm">
                    </div>
                    
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full mt-1 border-gray-200 rounded-md shadow-sm focus:border-teal-500 text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase">New Password</label>
                        <input type="password" name="password" placeholder="Leave blank to keep current" class="w-full mt-1 border-gray-200 rounded-md shadow-sm focus:border-teal-500 text-sm">
                    </div>

                    @if(Auth::user()->role === 'admin')
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase">System Role</label>
                            <select name="role" class="w-full mt-1 border-gray-200 rounded-md shadow-sm focus:border-teal-500 text-sm">
                                @foreach(['admin', 'manager', 'sales_staff'] as $role)
                                    <option value="{{ $role }}" {{ $user->role == $role ? 'selected' : '' }}>
                                        {{ ucfirst($role) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>

                <div class="pt-6 flex justify-end gap-3 border-t">
                    <a href="{{ route('users.index') }}" class="px-4 py-2 text-[10px] font-bold text-gray-400 uppercase hover:text-gray-600">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded-md text-[10px] font-bold uppercase shadow-sm hover:bg-teal-700">Update User</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>