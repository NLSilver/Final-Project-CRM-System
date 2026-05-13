@section('title', 'Leads')
<x-app-layout>
    <div class="max-w-4xl mx-auto lg:mt-8">
        <div class="flex items-center justify-between mb-6 px-2">
            <div class="flex items-center">
                <a href="{{ route('leads.index') }}" class="text-gray-400 hover:text-gray-600 transition mr-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <h1 class="text-xl font-bold text-gray-800 tracking-tight">New Sales Opportunity</h1>
            </div>
        </div>

        <form action="{{ route('leads.store') }}" method="POST" class="space-y-6 pb-20">
            @csrf
            <div class="bg-white shadow-sm border border-gray-200 rounded-sm overflow-hidden">
                <div class="bg-gray-50/50 border-b border-gray-100 px-4 py-3">
                    <h2 class="text-[11px] uppercase font-bold text-teal-700 tracking-wider">Opportunity Details</h2>
                </div>
                
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    <div class="md:col-span-2">
                        <label class="block text-[11px] uppercase font-bold text-gray-400 mb-1">Customer or Prospect Name</label>
                        <input type="text" name="name" required value="{{ old('name') }}"
                               class="w-full border-b border-gray-200 border-t-0 border-l-0 border-r-0 px-0 py-2 focus:ring-0 focus:border-teal-500 text-sm text-gray-700 placeholder-gray-300">
                    </div>

                    <div>
                        <label class="block text-[11px] uppercase font-bold text-gray-400 mb-1">Email Address</label>
                        <input type="email" name="email" required value="{{ old('email') }}"
                               class="w-full border-b border-gray-200 border-t-0 border-l-0 border-r-0 px-0 py-2 focus:ring-0 focus:border-teal-500 text-sm text-gray-700 placeholder-gray-300">
                    </div>

                    <div>
                        <label class="block text-[11px] uppercase font-bold text-gray-400 mb-1">Phone Number</label>
                        <input type="text" name="phone" required value="{{ old('phone') }}"
                               class="w-full border-b border-gray-200 border-t-0 border-l-0 border-r-0 px-0 py-2 focus:ring-0 focus:border-teal-500 text-sm text-gray-700 placeholder-gray-300">
                    </div>

                    <div>
                        <label class="block text-[11px] uppercase font-bold text-gray-400 mb-1">Source</label>
                        <input type="text" name="source" value="{{ old('source') }}"
                               class="w-full border-b border-gray-200 border-t-0 border-l-0 border-r-0 px-0 py-2 focus:ring-0 focus:border-teal-500 text-sm text-gray-700 placeholder-gray-300">
                    </div>

                    <div>
                        <label class="block text-[11px] uppercase font-bold text-gray-400 mb-1">Status</label>
                        <select name="status" class="w-full border-b border-gray-200 border-t-0 border-l-0 border-r-0 px-0 py-2 focus:ring-0 focus:border-teal-500 text-sm text-gray-700 bg-transparent">
                            @foreach(['New', 'Contacted', 'Qualified', 'Proposal Sent', 'Negotiation', 'Won', 'Lost'] as $status)
                                <option value="{{ $status }}">{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] uppercase font-bold text-gray-400 mb-1">Priority</label>
                        <select name="priority" class="w-full border-b border-gray-200 border-t-0 border-l-0 border-r-0 px-0 py-2 focus:ring-0 focus:border-teal-500 text-sm text-gray-700 bg-transparent">
                            <option value="Low">Low</option>
                            <option value="Medium" selected>Medium</option>
                            <option value="High">High</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] uppercase font-bold text-gray-400 mb-1">Expected Value (₱)</label>
                        <input type="number" step="0.01" name="expected_value" value="{{ old('expected_value') }}"
                               class="w-full border-b border-gray-200 border-t-0 border-l-0 border-r-0 px-0 py-2 focus:ring-0 focus:border-teal-500 text-sm font-mono placeholder-gray-300">
                    </div>

                    @if(in_array(auth()->user()->role, ['admin', 'manager']))
                    <div>
                        <label class="block text-[11px] uppercase font-bold text-gray-400 mb-1">Assign To</label>
                        <select name="assigned_user_id" class="w-full border-b border-gray-200 border-t-0 border-l-0 border-r-0 px-0 py-2 focus:ring-0 focus:border-teal-500 text-sm text-gray-700 bg-transparent">
                            <option value="">-- Select Assignee --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('assigned_user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div class="md:col-span-2">
                        <label class="block text-[11px] uppercase font-bold text-gray-400 mb-1">Notes</label>
                        <textarea name="notes" rows="3" class="w-full border-b border-gray-200 border-t-0 border-l-0 border-r-0 px-0 py-2 focus:ring-0 focus:border-teal-500 text-sm text-gray-600 bg-transparent">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-end gap-4 pt-4">
                <a href="{{ route('leads.index') }}" class="text-sm font-semibold text-gray-400 hover:text-gray-600 transition order-2 sm:order-1">Discard</a>
                <button type="submit" class="w-full sm:w-auto bg-teal-600 hover:bg-teal-700 text-white px-10 py-2.5 rounded-md text-sm font-bold shadow-md transition order-1 sm:order-2">
                    Create Lead
                </button>
            </div>
        </form>
    </div>
</x-app-layout>