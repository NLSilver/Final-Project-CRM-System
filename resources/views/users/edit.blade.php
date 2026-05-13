@section('title', 'Edit User')
<x-app-layout>
    <div class="max-w-3xl mx-auto py-6 sm:py-10 px-4">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="bg-gray-50 border-b border-gray-100 px-8 py-4 flex justify-between items-center">
                <div>
                    <h2 class="text-xs font-bold uppercase text-gray-500 tracking-widest">Update Account Settings</h2>
                    <p class="text-[10px] text-gray-400 mt-0.5">Editing: {{ $user->email }}</p>
                </div>
            </div>
            
            <form action="{{ route('users.update', $user->id) }}" method="POST" class="p-8 space-y-8">
                @csrf @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required 
                               class="w-full border-b border-gray-200 px-0 py-2 focus:ring-0 focus:border-teal-500 text-sm bg-transparent font-medium text-gray-800">
                    </div>
                    
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required 
                               class="w-full border-b border-gray-200 px-0 py-2 focus:ring-0 focus:border-teal-500 text-sm bg-transparent font-medium text-gray-800">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Update Password</label>
                        <input type="password" name="password" placeholder="••••••••" 
                               class="w-full border-b border-gray-200 px-0 py-2 focus:ring-0 focus:border-teal-500 text-sm bg-transparent placeholder-gray-200">
                        <p class="mt-1 text-[9px] text-gray-400 italic">Leave empty to retain current password</p>
                    </div>

                    @if(Auth::user()->role === 'admin')
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">System Permissions</label>
                            <select name="role" required class="w-full border-b border-gray-200 px-0 py-2 focus:ring-0 focus:border-teal-500 text-sm bg-transparent font-bold text-teal-700">
                                @foreach(['admin' => 'Administrator', 'manager' => 'Manager', 'sales_staff' => 'Sales Staff'] as $val => $label)
                                    <option value="{{ $val }}" {{ old('role', $user->role) == $val ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        <input type="hidden" name="role" value="{{ $user->role }}">
                    @endif
                </div>

                <div class="pt-6 flex flex-col sm:flex-row justify-end items-center gap-6 border-t border-gray-50">
                    <a href="{{ route('users.index') }}" class="text-xs font-bold text-gray-400 uppercase hover:text-gray-600 transition order-2 sm:order-1">Cancel</a>
                    <button type="submit" class="w-full sm:w-auto px-10 py-3 bg-teal-600 text-white rounded-lg text-xs font-bold uppercase tracking-widest hover:bg-teal-700 shadow-md transition order-1 sm:order-2">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>