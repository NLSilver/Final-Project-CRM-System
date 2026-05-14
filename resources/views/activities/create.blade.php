@section('title', 'Log Activity')
<x-app-layout>
    <div class="max-w-4xl mx-auto lg:mt-8 pb-20">
        <div class="flex items-center gap-4 mb-6 px-2">
            <a href="{{ url()->previous() }}" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h1 class="text-xl font-bold text-gray-800 tracking-tight">Log Interaction</h1>
        </div>

        <form action="{{ route('activities.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="bg-white shadow-sm border border-gray-200 rounded-sm overflow-hidden">
                <div class="bg-gray-50/50 border-b border-gray-100 px-6 py-4">
                    <h2 class="text-[11px] uppercase font-bold text-teal-700 tracking-widest">
                        Activity Details 
                        @if(request()->has('id'))
                            <span class="text-gray-400 ml-2">— Linked to {{ ucfirst(request('type')) }} #{{ request('id') }}</span>
                        @endif
                    </h2>
                </div>
                
                <div class="p-6 sm:p-8 grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-8">
                    <div>
                        <label class="block text-[11px] uppercase font-bold text-gray-400 mb-1">Activity Type *</label>
                        <select name="activity_type" 
                                class="w-full border-b border-gray-200 px-0 py-2 focus:ring-0 focus:border-teal-500 text-sm font-bold uppercase bg-transparent">
                            <option value="call" {{ old('activity_type') == 'call' ? 'selected' : '' }}>Call</option>
                            <option value="email" {{ old('activity_type') == 'email' ? 'selected' : '' }}>Email</option>
                            <option value="meeting" {{ old('activity_type') == 'meeting' ? 'selected' : '' }}>Meeting</option>
                            <option value="note" {{ old('activity_type') == 'note' ? 'selected' : '' }}>General Note</option>
                        </select>
                        @error('activity_type') <p class="text-[10px] text-red-500 mt-1 font-bold uppercase">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-[11px] uppercase font-bold text-gray-400 mb-1">Date *</label>
                        <input type="date" name="activity_date" value="{{ old('activity_date', date('Y-m-d')) }}" 
                               class="w-full border-b border-gray-200 px-0 py-2 focus:ring-0 focus:border-teal-500 text-sm bg-transparent">
                        @error('activity_date') <p class="text-[10px] text-red-500 mt-1 font-bold uppercase">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[11px] uppercase font-bold {{ $errors->has('description') ? 'text-red-500' : 'text-gray-400' }} mb-1">Description *</label>
                        <textarea name="description" rows="3" placeholder="What was discussed or performed?"
                                  class="w-full border-b {{ $errors->has('description') ? 'border-red-500' : 'border-gray-200' }} px-0 py-2 focus:ring-0 focus:border-teal-500 text-sm italic bg-transparent">{{ old('description') }}</textarea>
                        @error('description') <p class="text-[10px] text-red-500 mt-1 font-bold uppercase">{{ $message }}</p> @enderror
                    </div>

                    @if(request()->has('id'))
                        <input type="hidden" name="customer_id" value="{{ request('type') == 'customer' ? request('id') : '' }}">
                        <input type="hidden" name="lead_id" value="{{ request('type') == 'lead' ? request('id') : '' }}">
                    @else
                        <div>
                            <label class="block text-[11px] uppercase font-bold text-gray-400 mb-1">Link to Customer</label>
                            <select name="customer_id" id="customer_select" onchange="toggleSelects('customer')"
                                    class="w-full border-b border-gray-200 px-0 py-2 focus:ring-0 focus:border-teal-500 text-sm bg-transparent disabled:opacity-30 disabled:cursor-not-allowed">
                                <option value="">Select Customer</option>
                                @foreach($customers as $c)
                                    <option value="{{ $c->id }}" {{ old('customer_id') == $c->id ? 'selected' : '' }}>
                                        {{ $c->first_name }} {{ $c->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-[11px] uppercase font-bold text-gray-400 mb-1">Link to Lead</label>
                            <select name="lead_id" id="lead_select" onchange="toggleSelects('lead')"
                                    class="w-full border-b border-gray-200 px-0 py-2 focus:ring-0 focus:border-teal-500 text-sm bg-transparent disabled:opacity-30 disabled:cursor-not-allowed">
                                <option value="">Select Lead</option>
                                @foreach($leads as $l)
                                    <option value="{{ $l->id }}" {{ old('lead_id') == $l->id ? 'selected' : '' }}>
                                        {{ $l->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-end gap-4">
                <a href="{{ url()->previous() }}" class="text-sm font-bold text-gray-400 hover:text-gray-600 transition order-2 sm:order-1">Discard</a>
                <button type="submit" class="w-full sm:w-auto bg-teal-600 hover:bg-teal-700 text-white px-10 py-2.5 rounded-md text-sm font-bold shadow-md transition uppercase tracking-widest order-1 sm:order-2">
                    Save Log
                </button>
            </div>
        </form>
    </div>

    <script>
        function toggleSelects(changedType) {
            const customerSelect = document.getElementById('customer_select');
            const leadSelect = document.getElementById('lead_select');

            if (!customerSelect || !leadSelect) return;

            if (changedType === 'customer') {
                leadSelect.disabled = customerSelect.value !== "";
                if (customerSelect.value !== "") leadSelect.value = "";
            } else {
                customerSelect.disabled = leadSelect.value !== "";
                if (leadSelect.value !== "") customerSelect.value = "";
            }
        }

        window.addEventListener('DOMContentLoaded', () => {
            const customerSelect = document.getElementById('customer_select');
            const leadSelect = document.getElementById('lead_select');
            
            if (customerSelect && customerSelect.value !== "") toggleSelects('customer');
            if (leadSelect && leadSelect.value !== "") toggleSelects('lead');
        });
    </script>
</x-app-layout>