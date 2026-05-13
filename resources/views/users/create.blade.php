@section('title', 'Users')
<x-app-layout>
    <div class="max-w-3xl mx-auto py-10">
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-8">
            <h2 class="text-sm font-bold uppercase text-gray-700 mb-6 border-b pb-4">Create New User</h2>
            
            <form action="{{ route('users.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase">Full Name</label>
                        <input type="text" name="name" required class="w-full mt-1 border-gray-200 rounded-md text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase">Email Address</label>
                        <input type="email" name="email" required class="w-full mt-1 border-gray-200 rounded-md text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase">Password</label>
                        <input type="password" name="password" required class="w-full mt-1 border-gray-200 rounded-md text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase">System Role</label>
                        <select name="role" class="w-full mt-1 border-gray-200 rounded-md text-sm">
                            <option value="admin">Admin</option>
                            <option value="manager">Manager</option>
                            <option value="sales_staff">Staff</option>
                        </select>
                    </div>
                </div>

                <div class="pt-6 flex justify-end gap-3 border-t">
                    <a href="{{ route('users.index') }}" class="px-4 py-2 text-[10px] font-bold text-gray-400 uppercase hover:text-gray-600">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded-md text-[10px] font-bold uppercase hover:bg-teal-700">Create User</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>