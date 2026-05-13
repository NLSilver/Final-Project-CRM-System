<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center p-4 bg-gray-50">
        <div class="w-full max-w-sm bg-white border border-gray-100 rounded-2xl shadow-sm p-8">
            
            <div class="text-center mb-8">
                <img src="{{ asset('images/logo.png') }}" 
                     alt="NullCRM Logo" 
                     class="mx-auto h-16 w-auto mb-4">
                     
                <h1 class="text-xl font-black text-gray-800 uppercase tracking-tight">System Login</h1>
                <p class="text-[10px] font-bold text-gray-600 uppercase tracking-widest mt-1">Authorized access only</p>
            </div>

            @if ($errors->any())
                <div class="mb-6 p-3 bg-red-50 border border-red-100 rounded-lg">
                    <ul class="text-[10px] font-bold text-red-500 uppercase tracking-widest text-center">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-[10px] font-bold text-gray-600 uppercase tracking-widest mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus 
                        class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-4 text-sm focus:border-teal-500 focus:ring-teal-500 transition">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-600 uppercase tracking-widest mb-2">Password</label>
                    <input type="password" name="password" required 
                        class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-4 text-sm focus:border-teal-500 focus:ring-teal-500 transition">
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" name="remember" id="remember_me" class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                        <label for="remember_me" class="ml-2 text-[10px] font-bold text-gray-600 uppercase tracking-widest">Remember Me</label>
                    </div>
                </div>

                <button type="submit" 
                    class="w-full bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 rounded-lg text-sm transition shadow-sm hover:shadow-md">
                    SIGN IN
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-[10px] font-bold text-gray-600 uppercase tracking-widest">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="text-teal-600 hover:text-teal-800 underline transition">
                        Register here
                    </a>
                </p>
            </div>

        </div>
    </div>
</x-guest-layout>