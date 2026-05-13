@section('title', 'Follow-Ups')
<x-app-layout>
    <div class="max-w-4xl mx-auto mt-8">
        <form action="{{ route('follow-ups.update', $followUp->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="bg-white shadow-sm border border-gray-200 rounded-sm p-8">
                <h2 class="text-[11px] uppercase font-bold text-teal-700 mb-6 tracking-widest">Edit Follow-up</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-[11px] uppercase font-bold text-gray-400 mb-1">Subject *</label>
                        <input type="text" name="title" value="{{ old('title', $followUp->title) }}" required 
                               class="w-full border-b border-gray-200 py-2 text-sm font-medium">
                    </div>

                    <div>
                        <label class="block text-[11px] uppercase font-bold text-gray-400 mb-1">Assign to Lead</label>
                        <select name="lead_id" id="lead_select" onchange="toggleSelects('lead_select', 'customer_select')" 
                                class="w-full border-b border-gray-200 py-2 text-sm font-bold uppercase {{ $followUp->customer_id ? 'opacity-50' : '' }}" 
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
                                class="w-full border-b border-gray-200 py-2 text-sm font-bold uppercase {{ $followUp->lead_id ? 'opacity-50' : '' }}" 
                                {{ $followUp->lead_id ? 'disabled' : '' }}>
                            <option value="">None</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}" {{ old('customer_id', $followUp->customer_id) == $c->id ? 'selected' : '' }}>{{ $c->first_name }} {{ $c->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-[10px] uppercase font-bold text-gray-400 mb-1">Assigned Staff</label>
                        <p id="assignedStaffDisplay" class="text-sm font-bold text-gray-800">
                            {{ $followUp->user->name ?? 'Please select a Customer or Lead' }}
                        </p>
                    </div>
                    
                    <div>
                        <label class="block text-[11px] uppercase font-bold text-gray-400 mb-1">Due Date *</label>
                        <input type="date" name="due_date" value="{{ old('due_date', date('Y-m-d')) }}" required
                               class="w-full border-b border-gray-200 px-0 py-2 focus:ring-0 focus:border-teal-500 text-sm">
                        @error('due_date') <p class="text-[10px] text-red-500 mt-1 font-bold uppercase">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[11px] uppercase font-bold text-gray-400 mb-1">Notes</label>
                        <textarea name="description" rows="2" class="w-full border-b border-gray-200 py-2 text-sm italic">{{ old('description', $followUp->description) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-x-6 mt-8">
                <a href="{{ url()->previous() }}" class="text-sm font-bold text-gray-400 uppercase hover:text-gray-600 transition">Cancel</a>
                <button type="submit" class="bg-teal-600 text-white px-8 py-2 rounded-md text-sm font-bold uppercase shadow-lg hover:bg-teal-700 transition">Update Follow-up</button>
            </div>
        </form>
    </div>

    <script>
    function toggleSelects(activeId, disableId) {
        const active = document.getElementById(activeId);
        const target = document.getElementById(disableId);
        target.disabled = (active.value !== "");
        target.classList.toggle('opacity-50', active.value !== "");
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
    </script>
</x-app-layout>