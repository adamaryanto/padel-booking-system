<x-guest-layout>
    <div class="mb-6 flex items-center">
        <a href="{{ route('welcome') }}" class="text-[10px] font-black text-white/40 uppercase tracking-[0.3em] hover:text-neon transition flex items-center group">
            <svg class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Home
        </a>
    </div>

    <div class="mb-10">
        <h2 class="text-3xl font-black text-white italic transition-all uppercase tracking-tighter font-heading">READY TO <span class="text-neon">PLAY?</span></h2>
        <p class="text-gray-500 mt-2 font-bold uppercase tracking-widest text-[9px]">Enter your tactical credentials</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-6 bg-neon/10 border border-neon/20 text-neon p-4 rounded-2xl text-xs font-bold" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div class="space-y-3">
            <x-input-label for="email" value="Email Address" class="font-black text-white/40 uppercase tracking-widest text-[10px] pl-2" />
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" 
                class="block w-full bg-dark border border-white/5 focus:border-neon focus:ring-1 focus:ring-neon rounded-2xl p-5 text-white font-bold transition-all placeholder:text-gray-700 outline-none"
                placeholder="NAME@DOMAIN.COM">
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-[10px] font-bold uppercase tracking-widest" />
        </div>

        <!-- Password -->
        <div class="space-y-3">
            <div class="flex justify-between items-center px-2">
                <x-input-label for="password" value="Password" class="font-black text-white/40 uppercase tracking-widest text-[10px]" />
                @if (Route::has('password.request'))
                    <a class="text-[9px] font-black text-neon uppercase tracking-widest hover:text-white transition" href="{{ route('password.request') }}">
                        Forgot?
                    </a>
                @endif
            </div>
            <input id="password" type="password" name="password" required autocomplete="current-password" 
                class="block w-full bg-dark border border-white/5 focus:border-neon focus:ring-1 focus:ring-neon rounded-2xl p-5 text-white font-bold transition-all placeholder:text-gray-700 outline-none"
                placeholder="••••••••">
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-[10px] font-bold uppercase tracking-widest" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center space-x-3 px-2">
            <input id="remember_me" type="checkbox" class="rounded-lg bg-dark border-white/10 text-neon focus:ring-neon/50 w-5 h-5" name="remember">
            <label for="remember_me" class="text-xs font-bold text-gray-500 uppercase tracking-widest cursor-pointer">Stay Authenticated</label>
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full bg-neon text-dark py-5 rounded-[2rem] font-black text-base uppercase tracking-tighter hover:scale-[1.02] transition shadow-[0_0_30px_rgba(190,242,100,0.3)] flex justify-center items-center group active:scale-95">
                Secure Access
                <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </button>
        </div>

        <div class="mt-8 text-center">
            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">
                New Athlete? 
                <a href="{{ route('register') }}" class="text-neon font-black hover:text-white transition border-b-2 border-neon/30 ml-2">Join the Club</a>
            </p>
        </div>
    </form>
</x-guest-layout>

