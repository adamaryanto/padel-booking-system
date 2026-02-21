<x-guest-layout>
    <div class="mb-10">
        <h2 class="text-4xl font-black text-white italic transition-all uppercase tracking-tighter font-heading">UPDATE <span class="text-neon">SANDI</span></h2>
        <p class="text-gray-500 mt-2 font-bold uppercase tracking-widest text-[10px]">Secure your tactical access</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="space-y-3">
            <x-input-label for="email" :value="__('Email')" class="font-black text-white/40 uppercase tracking-widest text-[10px] pl-2" />
            <x-text-input id="email" class="block w-full bg-dark border-transparent focus:ring-2 focus:ring-neon rounded-2xl p-5 text-white font-bold transition-all placeholder:text-gray-700" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-6 space-y-3">
            <x-input-label for="password" :value="__('New Password')" class="font-black text-white/40 uppercase tracking-widest text-[10px] pl-2" />
            <x-text-input id="password" class="block w-full bg-dark border-transparent focus:ring-2 focus:ring-neon rounded-2xl p-5 text-white font-bold transition-all placeholder:text-gray-700" type="password" name="password" required autocomplete="new-password" placeholder="MIN. 8 CHARACTERS" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-6 space-y-3">
            <x-input-label for="password_confirmation" :value="__('Confirm New Password')" class="font-black text-white/40 uppercase tracking-widest text-[10px] pl-2" />
            <x-text-input id="password_confirmation" class="block w-full bg-dark border-transparent focus:ring-2 focus:ring-neon rounded-2xl p-5 text-white font-bold transition-all
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" placeholder="REPEAT PASSWORD" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-10">
            <button type="submit" class="w-full bg-neon text-dark py-5 rounded-[2rem] font-black text-lg uppercase tracking-tighter hover:scale-105 transition shadow-[0_0_30px_rgba(190,242,100,0.2)] flex justify-center items-center group">
                {{ __('Reset Access') }}
                <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </button>
        </div>
    </form>
</x-guest-layout>
