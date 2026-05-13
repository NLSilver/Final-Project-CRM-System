@section('title', 'Create User')
<x-app-layout>
    <div class="max-w-3xl mx-auto py-6 sm:py-10 px-4">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="bg-gray-50 border-b border-gray-100 px-8 py-4">
                <h2 class="text-xs font-bold uppercase text-gray-500 tracking-widest">Register New System Account</h2>
            </div>
            
            <form action="{{ route('users.store') }}" method="POST" class="p-8 space-y-8">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Full Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required 
                               class="w-full border-b border-gray-200 px-0 py-2 focus:ring-0 focus:border-teal-500 text-sm bg-transparent placeholder-gray-300" placeholder="e.g. John Doe">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" required 
                               class="w-full border-b border-gray-200 px-0 py-2 focus:ring-0 focus:border-teal-500 text-sm bg-transparent placeholder-gray-300" placeholder="name@nullcrm.com">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Initial Password</label>
                        <input type="password" name="password" required 
                               class="w-full border-b border-gray-200 px-0 py-2 focus:ring-0 focus:border-teal-500 text-sm bg-transparent">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">System Role</label>
                        <select name="role" required class="w-full border-b border-gray-200 px-0 py-2 focus:ring-0 focus:border-teal-500 text-sm bg-transparent">
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                            <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>Manager</option>
                            <option value="sales_staff" {{ old('role') == 'sales_staff' ? 'selected' : '' }}>Sales Staff</option>
                        </select>
                    </div>
                </div>

                <div class="pt-6 flex flex-col sm:flex-row justify-end items-center gap-6 border-t border-gray-50">
                    <a href="{{ route('users.index') }}" class="text-xs font-bold text-gray-400 uppercase hover:text-gray-600 transition order-2 sm:order-1">Discard Changes</a>
                    <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-teal-600 text-white rounded-lg text-xs font-bold uppercase tracking-widest hover:bg-teal-700 shadow-md transition order-1 sm:order-2">
                        Register User
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>