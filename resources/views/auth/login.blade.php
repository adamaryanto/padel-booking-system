<x-guest-layout>
    <div class="mb-10">
        <h2 class="text-4xl font-black text-white italic transition-all uppercase tracking-tighter font-heading">WELCOME <span class="text-neon">BACK</span></h2>
        <p class="text-gray-500 mt-2 font-bold uppercase tracking-widest text-[10px]">Access your battle arena</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="space-y-3">
            <x-input-label for="email" :value="__('Email')" class="font-black text-white/40 uppercase tracking-widest text-[10px] pl-2" />
            <x-text-input id="email" class="block w-full bg-dark border-transparent focus:ring-2 focus:ring-neon rounded-2xl p-5 text-white font-bold transition-all placeholder:text-gray-700" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="NAME@DOMAIN.COM" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-8 space-y-3">
            <div class="flex justify-between items-center px-2">
                <x-input-label for="password" :value="__('Password')" class="font-black text-white/40 uppercase tracking-widest text-[10px]" />
                @if (Route::has('password.request'))
                    <a class="text-[10px] font-black text-neon uppercase tracking-widest hover:text-white transition" href="{{ route('password.request') }}">
                        {{ __('Forgot?') }}
                    </a>
                @endif
            </div>
            <x-text-input id="password" class="block w-full bg-dark border-transparent focus:ring-2 focus:ring-neon rounded-2xl p-5 text-white font-bold transition-all placeholder:text-gray-700"
                            type="password"
                            name="password"
                            required autocomplete="current-password" placeholder="••••••••" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-8">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" type="checkbox" class="rounded border-transparent bg-dark text-neon shadow-sm focus:ring-neon w-5 h-5 transition" name="remember">
                <span class="ms-3 text-[10px] font-black text-white/30 uppercase tracking-widest group-hover:text-white/50 transition">{{ __('Stay in session') }}</span>
            </label>
        </div>

        <div class="mt-10">
            <button type="submit" class="w-full bg-neon text-dark py-5 rounded-[2rem] font-black text-lg uppercase tracking-tighter hover:scale-105 transition shadow-[0_0_30px_rgba(190,242,100,0.2)] flex justify-center items-center group">
                {{ __('Enter Hub') }}
                <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
            </button>
        </div>

        <div class="mt-12 text-center border-t border-white/5 pt-10">
            <p class="text-[10px] font-black text-white/40 uppercase tracking-widest">
                Need an account? 
                <a href="{{ route('register') }}" class="text-neon hover:text-white transition ml-2">Join community</a>
            </p>
        </div>
    </form>
</x-guest-layout>
