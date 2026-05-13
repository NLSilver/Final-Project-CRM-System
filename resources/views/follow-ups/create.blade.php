@section('title', 'Follow-Ups')
<x-app-layout>
    <div class="max-w-4xl mx-auto lg:mt-8 pb-20">
        <div class="flex items-center gap-4 mb-6 px-2">
            <a href="{{ url()->previous() }}" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h1 class="text-xl font-bold text-gray-800 tracking-tight">Schedule Follow-up</h1>
        </div>

        <form action="{{ route('follow-ups.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="bg-white shadow-sm border border-gray-200 rounded-sm">
                <div class="bg-gray-50/50 border-b border-gray-100 px-4 py-3 sm:px-6 sm:py-4">
                    <h2 class="text-[11px] uppercase font-bold text-teal-700 tracking-widest">
                        Task Details
                        @if(request()->has('id'))
                            <span class="text-gray-400 block sm:inline">— Linked to {{ ucfirst(request('type')) }} #{{ request('id') }}</span>
                        @endif
                    </h2>
                </div>
                
                <div class="p-4 sm:p-8 grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-8">
                    <div class="md:col-span-2">
                        <label class="block text-[11px] uppercase font-bold text-gray-400 mb-1">Subject / Title *</label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                               class="w-full border-b border-gray-200 px-0 py-2 focus:ring-0 focus:border-teal-500 text-sm font-medium bg-transparent">
                        @error('title') <p class="text-[10px] text-red-500 mt-1 font-bold uppercase">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-[11px] uppercase font-bold text-gray-400 mb-1">Assign to Lead</label>
                        <select name="lead_id" id="lead_select" onchange="toggleSelects('lead_select', 'customer_select')"
                                class="w-full border-b border-gray-200 px-0 py-2 focus:ring-0 focus:border-teal-500 text-sm font-bold uppercase bg-transparent {{ request('type') == 'customer' ? 'opacity-50 cursor-not-allowed' : '' }}"
                                {{ request('type') == 'customer' ? 'disabled' : '' }}>
                            <option value="">None</option>
                            @foreach($leads as $l)
                                <option value="{{ $l->id }}" {{ (old('lead_id') == $l->id || (request('type') == 'lead' && request('id') == $l->id)) ? 'selected' : '' }}>
                                    {{ $l->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] uppercase font-bold text-gray-400 mb-1">Assign to Customer</label>
                        <select name="customer_id" id="customer_select" onchange="toggleSelects('customer_select', 'lead_select')"
                                class="w-full border-b border-gray-200 px-0 py-2 focus:ring-0 focus:border-teal-500 text-sm font-bold uppercase bg-transparent {{ request('type') == 'lead' ? 'opacity-50 cursor-not-allowed' : '' }}"
                                {{ request('type') == 'lead' ? 'disabled' : '' }}>
                            <option value="">None</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}" {{ (old('customer_id') == $c->id || (request('type') == 'customer' && request('id') == $c->id)) ? 'selected' : '' }}>
                                    {{ $c->first_name }} {{ $c->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-[10px] uppercase font-bold text-gray-400 mb-1">Target Personnel</label>
                        <p id="assignedStaffDisplay" class="text-sm font-bold text-gray-800 py-2">
                            Please select a Customer or Lead
                        </p>
                    </div>
                    
                    <div>
                        <label class="block text-[11px] uppercase font-bold text-gray-400 mb-1">Due Date *</label>
                        <input type="date" name="due_date" value="{{ old('due_date', date('Y-m-d')) }}" required
                               class="w-full border-b border-gray-200 px-0 py-2 focus:ring-0 focus:border-teal-500 text-sm bg-transparent">
                        @error('due_date') <p class="text-[10px] text-red-500 mt-1 font-bold uppercase">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[11px] uppercase font-bold text-gray-400 mb-1">Task Notes</label>
                        <textarea name="description" rows="2" placeholder="Specific instructions for this follow-up..."
                                  class="w-full border-b border-gray-200 px-0 py-2 focus:ring-0 focus:border-teal-500 text-sm italic bg-transparent">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-end gap-4 mt-8">
                <a href="{{ url()->previous() }}" class="text-sm font-bold text-gray-400 uppercase hover:text-gray-600 transition order-2 sm:order-1">Cancel</a>
                <button type="submit" class="w-full sm:w-auto bg-teal-600 text-white px-10 py-2.5 rounded-md text-sm font-bold uppercase shadow-lg hover:bg-teal-700 transition tracking-widest order-1 sm:order-2">
                    Schedule Task
                </button>
            </div>
        </form>
    </div>

    <script>
    function toggleSelects(activeId, disableId) {
        const active = document.getElementById(activeId);
        const target = document.getElementById(disableId);
        if (active.value !== "") {
            target.disabled = true;
            target.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            target.disabled = false;
            target.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }

    const assignments = {
        customers: @json($customers->pluck('assigned_user_id', 'id')),
        leads: @json($leads->pluck('assigned_user_id', 'id')),
        users: @json(\App\Models\User::all()->pluck('name', 'id'))
    };

    function updateAssignmentDisplay() {
        const custId = document.getElementById('customer_select')?.value;
        const leadId = document.getElementById('lead_select')?.value;
        const display = document.getElementById('assignedStaffDisplay');
        let staffId = null;
        if (custId && assignments.customers[custId]) staffId = assignments.customers[custId];
        else if (leadId && assignments.leads[leadId]) staffId = assignments.leads[leadId];
        display.innerText = staffId ? assignments.users[staffId] : 'Select a Customer or Lead';
    }

    document.getElementById('customer_select')?.addEventListener('change', updateAssignmentDisplay);
    document.getElementById('lead_select')?.addEventListener('change', updateAssignmentDisplay);
    window.addEventListener('load', () => {
        if (document.getElementById('lead_select').value !== "") toggleSelects('lead_select', 'customer_select');
        if (document.getElementById('customer_select').value !== "") toggleSelects('customer_select', 'lead_select');
        updateAssignmentDisplay();
    });
    </script>
</x-app-layout>