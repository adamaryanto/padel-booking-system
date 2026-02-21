<x-guest-layout>
    <div class="mb-10">
        <h2 class="text-4xl font-black text-white italic transition-all uppercase tracking-tighter font-heading">RECOVER <span class="text-neon">ACCESS</span></h2>
        <p class="text-gray-500 mt-2 font-bold uppercase tracking-widest text-[10px]">No athlete left behind</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="space-y-3">
            <x-input-label for="email" :value="__('Registered Email')" class="font-black text-white/40 uppercase tracking-widest text-[10px] pl-2" />
            <x-text-input id="email" class="block w-full bg-dark border-transparent focus:ring-2 focus:ring-neon rounded-2xl p-5 text-white font-bold transition-all placeholder:text-gray-700" type="email" name="email" :value="old('email')" required autofocus placeholder="NAME@DOMAIN.COM" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-10">
            <button type="submit" class="w-full bg-neon text-dark py-5 rounded-[2rem] font-black text-lg uppercase tracking-tighter hover:scale-105 transition shadow-[0_0_30px_rgba(190,242,100,0.2)] flex justify-center items-center group">
                {{ __('Request Reset') }}
                <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </button>
        </div>

        <div class="mt-12 text-center">
            <a href="{{ route('login') }}" class="text-[10px] font-black text-neon uppercase tracking-widest hover:text-white transition">Back to Headquarters</a>
        </div>
    </form>
</x-guest-layout>
