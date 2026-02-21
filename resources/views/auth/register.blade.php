<x-guest-layout>
    <div class="mb-10">
        <h2 class="text-4xl font-black text-white italic transition-all uppercase tracking-tighter font-heading">JOIN THE <span class="text-neon">ELITE</span></h2>
        <p class="text-gray-500 mt-2 font-bold uppercase tracking-widest text-[10px]">Create your athlete profile</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="space-y-3">
            <x-input-label for="name" :value="__('Full Name')" class="font-black text-white/40 uppercase tracking-widest text-[10px] pl-2" />
            <x-text-input id="name" class="block w-full bg-dark border-transparent focus:ring-2 focus:ring-neon rounded-2xl p-5 text-white font-bold transition-all placeholder:text-gray-700" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="JOHN DOE" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-6 space-y-3">
            <x-input-label for="email" :value="__('Email Address')" class="font-black text-white/40 uppercase tracking-widest text-[10px] pl-2" />
            <x-text-input id="email" class="block w-full bg-dark border-transparent focus:ring-2 focus:ring-neon rounded-2xl p-5 text-white font-bold transition-all placeholder:text-gray-700" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="NAME@DOMAIN.COM" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-6 space-y-3">
            <x-input-label for="password" :value="__('Password')" class="font-black text-white/40 uppercase tracking-widest text-[10px] pl-2" />

            <x-text-input id="password" class="block w-full bg-dark border-transparent focus:ring-2 focus:ring-neon rounded-2xl p-5 text-white font-bold transition-all placeholder:text-gray-700"
                            type="password"
                            name="password"
                            required autocomplete="new-password" placeholder="MIN. 8 CHARACTERS" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-6 space-y-3">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="font-black text-white/40 uppercase tracking-widest text-[10px] pl-2" />

            <x-text-input id="password_confirmation" class="block w-full bg-dark border-transparent focus:ring-2 focus:ring-neon rounded-2xl p-5 text-white font-bold transition-all placeholder:text-gray-700"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" placeholder="REPEAT PASSWORD" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-10">
            <button type="submit" class="w-full bg-neon text-dark py-5 rounded-[2rem] font-black text-lg uppercase tracking-tighter hover:scale-105 transition shadow-[0_0_30px_rgba(190,242,100,0.2)] flex justify-center items-center group">
                {{ __('Start Journey') }}
                <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
            </button>
        </div>

        <div class="mt-12 text-center border-t border-white/5 pt-10">
            <p class="text-[10px] font-black text-white/40 uppercase tracking-widest">
                Already registered? 
                <a href="{{ route('login') }}" class="text-neon hover:text-white transition ml-2">Sign in here</a>
            </p>
        </div>
    </form>
</x-guest-layout>
