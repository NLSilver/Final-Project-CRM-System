@section('title', 'Add Customer')
<x-app-layout>
    <div class="max-w-4xl mx-auto lg:mt-8">
        <div class="flex items-center justify-between mb-6 px-2">
            <div class="flex items-center">
                <a href="{{ route('customers.index') }}" class="text-gray-400 hover:text-gray-600 transition mr-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <h1 class="text-xl font-bold text-gray-800 tracking-tight">Add New Customer</h1>
            </div>
        </div>

        <form action="{{ route('customers.store') }}" method="POST" class="space-y-6 pb-20">
            @csrf
            
            <div class="bg-white shadow-sm border border-gray-200 rounded-sm overflow-hidden">
                <div class="bg-gray-50/50 border-b border-gray-100 px-4 py-3">
                    <h2 class="text-[11px] uppercase font-bold text-teal-700 tracking-wider">Contact Information</h2>
                </div>
                
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    <div class="col-span-1">
                        <label class="block text-[11px] uppercase font-bold text-gray-400 mb-1">First Name</label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" required 
                               class="w-full border-b border-gray-200 border-t-0 border-l-0 border-r-0 px-0 py-2 focus:ring-0 focus:border-teal-500 text-sm text-gray-700 bg-transparent placeholder-gray-300"
                               placeholder="e.g. John">
                    </div>

                    <div class="col-span-1">
                        <label class="block text-[11px] uppercase font-bold text-gray-400 mb-1">Last Name</label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" required 
                               class="w-full border-b border-gray-200 border-t-0 border-l-0 border-r-0 px-0 py-2 focus:ring-0 focus:border-teal-500 text-sm text-gray-700 bg-transparent placeholder-gray-300"
                               placeholder="e.g. Doe">
                    </div>

                    <div class="col-span-1">
                        <label class="block text-[11px] uppercase font-bold text-gray-400 mb-1">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" required 
                               class="w-full border-b border-gray-200 border-t-0 border-l-0 border-r-0 px-0 py-2 focus:ring-0 focus:border-teal-500 text-sm bg-transparent placeholder-gray-300"
                               placeholder="john.doe@example.com">
                    </div>

                    <div class="col-span-1">
                        <label class="block text-[11px] uppercase font-bold text-gray-400 mb-1">Contact Number</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" required
                               class="w-full border-b border-gray-200 border-t-0 border-l-0 border-r-0 px-0 py-2 focus:ring-0 focus:border-teal-500 text-sm text-gray-700 bg-transparent placeholder-gray-300"
                               placeholder="09123456789">
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-sm border border-gray-200 rounded-sm overflow-hidden">
                <div class="bg-gray-50/50 border-b border-gray-100 px-4 py-3">
                    <h2 class="text-[11px] uppercase font-bold text-teal-700 tracking-wider">Company & Classification</h2>
                </div>
                
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    <div class="col-span-1">
                        <label class="block text-[11px] uppercase font-bold text-gray-400 mb-1">Company Name</label>
                        <input type="text" name="company_name" value="{{ old('company_name') }}"
                               class="w-full border-b border-gray-200 border-t-0 border-l-0 border-r-0 px-0 py-2 focus:ring-0 focus:border-teal-500 text-sm text-gray-700 bg-transparent placeholder-gray-300"
                               placeholder="e.g. ABC Corp">
                    </div>

                    <div class="col-span-1">
                        <label class="block text-[11px] uppercase font-bold text-gray-400 mb-1">Status</label>
                        <select name="status" class="w-full border-b border-gray-200 border-t-0 border-l-0 border-r-0 px-0 py-2 focus:ring-0 focus:border-teal-500 text-sm text-gray-700 bg-transparent">
                            <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[11px] uppercase font-bold text-gray-400 mb-1">Office Address</label>
                        <input type="text" name="address" value="{{ old('address') }}"
                               class="w-full border-b border-gray-200 border-t-0 border-l-0 border-r-0 px-0 py-2 focus:ring-0 focus:border-teal-500 text-sm text-gray-700 bg-transparent placeholder-gray-300"
                               placeholder="Street, City, Province">
                    </div>
                </div>
            </div>

            @if(in_array(auth()->user()->role, ['admin', 'manager']))
            <div class="bg-white shadow-sm border border-gray-200 rounded-sm overflow-hidden">
                <div class="bg-gray-50/50 border-b border-gray-100 px-4 py-3">
                    <h2 class="text-[11px] uppercase font-bold text-teal-700 tracking-wider">Internal Assignment</h2>
                </div>
                <div class="p-6">
                    <label class="block text-[11px] uppercase font-bold text-gray-400 mb-1">Assign To</label>
                    <select name="assigned_user_id" class="w-full border-b border-gray-200 border-t-0 border-l-0 border-r-0 px-0 py-2 focus:ring-0 focus:border-teal-500 text-sm text-gray-700 bg-transparent">
                        <option value="">-- Select Assignee --</option>
                        @foreach($staff as $member)
                            <option value="{{ $member->id }}" {{ old('assigned_user_id') == $member->id ? 'selected' : '' }}>
                                {{ $member->name }} ({{ ucfirst(str_replace('_', ' ', $member->role)) }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            @endif

            <div class="flex flex-col sm:flex-row items-center justify-end gap-4 pt-4">
                <a href="{{ route('customers.index') }}" class="text-sm font-semibold text-gray-400 hover:text-gray-600 transition order-2 sm:order-1">
                    Discard Changes
                </a>
                <button type="submit" class="w-full sm:w-auto bg-teal-600 hover:bg-teal-700 text-white px-8 py-2.5 rounded-md text-sm font-bold transition shadow-md order-1 sm:order-2">
                    Save Customer
                </button>
            </div>
        </form>
    </div>
</x-app-layout>