<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center p-4 bg-gray-50">
        <div class="w-full max-w-sm bg-white border border-gray-100 rounded-2xl shadow-sm p-8">
            <div class="text-center mb-8">
                <h1 class="text-xl font-black text-gray-800 uppercase tracking-tight">Forgot Password</h1>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Reset your account access</p>
            </div>

            <x-auth-session-status class="mb-4 text-center text-xs" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-4 text-sm focus:border-teal-500 focus:ring-teal-500 transition">
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs text-red-500" />
                </div>

                <button type="submit" class="w-full bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 rounded-lg text-sm transition shadow-sm">
                    SEND RESET LINK
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>