<x-guest-layout>
    <div class="mb-10 text-center">
        <h2 class="text-3xl font-black text-white italic uppercase tracking-tighter font-heading">RECOVER <span class="text-neon">ACCESS</span></h2>
        <p class="text-gray-500 mt-2 font-bold uppercase tracking-widest text-[9px]">Requesting deep-link to Headquarters</p>
    </div>

    <div class="bg-neon/5 border border-neon/10 rounded-2xl p-6 mb-10">
        <p class="text-[11px] font-medium text-gray-400 leading-relaxed text-center uppercase tracking-wider">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-6 bg-neon/10 border border-neon/20 text-neon p-4 rounded-2xl text-xs font-bold text-center" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-8">
        @csrf

        <!-- Email Address -->
        <div class="space-y-3">
            <x-input-label for="email" value="Registered Email" class="font-black text-white/40 uppercase tracking-widest text-[10px] pl-2" />
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus 
                class="block w-full bg-dark border border-white/5 focus:border-neon focus:ring-1 focus:ring-neon rounded-2xl p-5 text-white font-bold transition-all placeholder:text-gray-700 outline-none"
                placeholder="NAME@DOMAIN.COM">
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-[10px] font-bold uppercase tracking-widest" />
        </div>

        <button type="submit" class="w-full bg-neon text-dark py-5 rounded-[2rem] font-black text-base uppercase tracking-tighter hover:scale-[1.02] transition shadow-[0_0_30px_rgba(190,242,100,0.3)] flex justify-center items-center group active:scale-95">
            Send Reset Link
            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
        </button>

        <div class="mt-8 text-center">
            <a href="{{ route('login') }}" class="text-[10px] font-black text-neon uppercase tracking-widest hover:text-white transition">Back to Headquarters</a>
        </div>
    </form>
</x-guest-layout>
