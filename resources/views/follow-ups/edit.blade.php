@section('title', 'Follow-Ups')
<x-app-layout>
    <div class="max-w-4xl mx-auto lg:mt-8 pb-20">
        <div class="flex items-center gap-4 mb-6 px-2">
            <a href="{{ route('follow-ups.index') }}" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h1 class="text-xl font-bold text-gray-800 tracking-tight">Edit Task</h1>
        </div>

        <form action="{{ route('follow-ups.update', $followUp->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="bg-white shadow-sm border border-gray-200 rounded-sm">
                <div class="bg-gray-50/50 border-b border-gray-100 px-4 py-3 sm:px-6 sm:py-4">
                    <h2 class="text-[11px] uppercase font-bold text-teal-700 tracking-widest">Update Schedule</h2>
                </div>
                
                <div class="p-4 sm:p-8 grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-8">
                    <div class="md:col-span-2">
                        <label class="block text-[11px] uppercase font-bold text-gray-400 mb-1">Subject / Title *</label>
                        <input type="text" name="title" value="{{ old('title', $followUp->title) }}" required 
                               class="w-full border-b border-gray-200 px-0 py-2 focus:ring-0 focus:border-teal-500 text-sm font-medium bg-transparent">
                    </div>

                    <div>
                        <label class="block text-[11px] uppercase font-bold text-gray-400 mb-1">Assign to Lead</label>
                        <select name="lead_id" id="lead_select" onchange="toggleSelects('lead_select', 'customer_select')" 
                                class="w-full border-b border-gray-200 px-0 py-2 focus:ring-0 focus:border-teal-500 text-sm font-bold uppercase bg-transparent {{ $followUp->customer_id ? 'opacity-50 cursor-not-allowed' : '' }}" 
                                {{ $followUp->customer_id ? 'disabled' : '' }}>
                            <option value="">None</option>
                            @foreach($leads as $l)
                                <option value="{{ $l->id }}" {{ old('lead_id', $followUp->lead_id) == $l->id ? 'selected' : '' }}>{{ $l->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] uppercase font-bold text-gray-400 mb-1">Assign to Customer</label>
                        <select name="customer_id" id="customer_select" onchange="toggleSelects('customer_select', 'lead_select')" 
                                class="w-full border-b border-gray-200 px-0 py-2 focus:ring-0 focus:border-teal-500 text-sm font-bold uppercase bg-transparent {{ $followUp->lead_id ? 'opacity-50 cursor-not-allowed' : '' }}" 
                                {{ $followUp->lead_id ? 'disabled' : '' }}>
                            <option value="">None</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}" {{ old('customer_id', $followUp->customer_id) == $c->id ? 'selected' : '' }}>{{ $c->first_name }} {{ $c->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-[10px] uppercase font-bold text-gray-400 mb-1">Target Personnel</label>
                        <p id="assignedStaffDisplay" class="text-sm font-bold text-gray-800 py-2">
                            {{ $followUp->user->name ?? 'Please select a contact' }}
                        </p>
                    </div>
                    
                    <div>
                        <label class="block text-[11px] uppercase font-bold text-gray-400 mb-1">Due Date *</label>
                        <input type="date" name="due_date" value="{{ old('due_date', $followUp->due_date) }}" required
                               class="w-full border-b border-gray-200 px-0 py-2 focus:ring-0 focus:border-teal-500 text-sm bg-transparent">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[11px] uppercase font-bold text-gray-400 mb-1">Task Notes</label>
                        <textarea name="description" rows="2" class="w-full border-b border-gray-200 px-0 py-2 focus:ring-0 focus:border-teal-500 text-sm italic bg-transparent">{{ old('description', $followUp->description) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-end gap-4 mt-8">
                <a href="{{ url()->previous() }}" class="text-sm font-bold text-gray-400 uppercase hover:text-gray-600 transition order-2 sm:order-1">Discard</a>
                <button type="submit" class="w-full sm:w-auto bg-teal-600 text-white px-10 py-2.5 rounded-md text-sm font-bold uppercase shadow-lg hover:bg-teal-700 transition order-1 sm:order-2">
                    Update Task
                </button>
            </div>
        </form>
    </div>

    <script>
    function toggleSelects(activeId, disableId) {
        const active = document.getElementById(activeId);
        const target = document.getElementById(disableId);
        target.disabled = (active.value !== "");
        target.classList.toggle('opacity-50', active.value !== "");
        target.classList.toggle('cursor-not-allowed', active.value !== "");
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
        display.innerText = staffId ? assignments.users[staffId] : 'Please select a contact';
    }

    document.getElementById('customer_select')?.addEventListener('change', updateAssignmentDisplay);
    document.getElementById('lead_select')?.addEventListener('change', updateAssignmentDisplay);
    </script>
</x-app-layout>