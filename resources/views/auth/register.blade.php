<x-guest-layout>
    <div class="mb-10">
        <h2 class="text-3xl font-black text-white italic transition-all uppercase tracking-tighter font-heading">BECOME <span class="text-neon">ELITE</span></h2>
        <p class="text-gray-500 mt-2 font-bold uppercase tracking-widest text-[9px]">Start your championship journey</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        <!-- Name -->
        <div class="space-y-3">
            <x-input-label for="name" value="Full Name" class="font-black text-white/40 uppercase tracking-widest text-[10px] pl-2" />
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" 
                class="block w-full bg-dark border border-white/5 focus:border-neon focus:ring-1 focus:ring-neon rounded-2xl p-5 text-white font-bold transition-all placeholder:text-gray-700 outline-none"
                placeholder="YOUR NAME">
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-500 text-[10px] font-bold uppercase tracking-widest" />
        </div>

        <!-- Email Address -->
        <div class="space-y-3">
            <x-input-label for="email" value="Email Address" class="font-black text-white/40 uppercase tracking-widest text-[10px] pl-2" />
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" 
                class="block w-full bg-dark border border-white/5 focus:border-neon focus:ring-1 focus:ring-neon rounded-2xl p-5 text-white font-bold transition-all placeholder:text-gray-700 outline-none"
                placeholder="NAME@DOMAIN.COM">
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-[10px] font-bold uppercase tracking-widest" />
        </div>

        <!-- Password -->
        <div class="space-y-3">
            <x-input-label for="password" value="Password" class="font-black text-white/40 uppercase tracking-widest text-[10px] pl-2" />
            <input id="password" type="password" name="password" required autocomplete="new-password" 
                class="block w-full bg-dark border border-white/5 focus:border-neon focus:ring-1 focus:ring-neon rounded-2xl p-5 text-white font-bold transition-all placeholder:text-gray-700 outline-none"
                placeholder="MIN. 8 CHARACTERS">
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-[10px] font-bold uppercase tracking-widest" />
        </div>

        <!-- Confirm Password -->
        <div class="space-y-3">
            <x-input-label for="password_confirmation" value="Verify Password" class="font-black text-white/40 uppercase tracking-widest text-[10px] pl-2" />
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" 
                class="block w-full bg-dark border border-white/5 focus:border-neon focus:ring-1 focus:ring-neon rounded-2xl p-5 text-white font-bold transition-all placeholder:text-gray-700 outline-none"
                placeholder="REPEAT PASSWORD">
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full bg-neon text-dark py-5 rounded-[2rem] font-black text-base uppercase tracking-tighter hover:scale-[1.02] transition shadow-[0_0_30px_rgba(190,242,100,0.3)] flex justify-center items-center group active:scale-95">
                Initialize Account
                <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
            </button>
        </div>

        <div class="mt-8 text-center text-[10px] font-bold text-gray-500 uppercase tracking-widest">
            Already registered? 
            <a href="{{ route('login') }}" class="text-neon font-black hover:text-white transition border-b-2 border-neon/30 ml-2">Back to Base</a>
        </div>
    </form>
</x-guest-layout>
