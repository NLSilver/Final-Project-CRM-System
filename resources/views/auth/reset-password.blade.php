<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center p-4 bg-gray-50">
        <div class="w-full max-w-sm bg-white border border-gray-100 rounded-2xl shadow-sm p-8">
            <h1 class="text-xl font-black text-gray-800 uppercase tracking-tight text-center mb-8">New Password</h1>
            
            <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">
                
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email', $request->email) }}" required class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-4 text-sm">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Password</label>
                    <input type="password" name="password" required class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-4 text-sm">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Confirm Password</label>
                    <input type="password" name="password_confirmation" required class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-4 text-sm">
                </div>

                <button type="submit" class="w-full bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 rounded-lg text-sm transition shadow-sm">
                    RESET PASSWORD
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>