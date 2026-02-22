<x-guest-layout>
    <div class="mb-10 text-center">
        <h2 class="text-3xl font-black text-white italic uppercase tracking-tighter font-heading">UPDATE <span class="text-neon">ACCESS</span></h2>
        <p class="text-gray-500 mt-2 font-bold uppercase tracking-widest text-[9px]">Re-securing your tactical status</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="space-y-3">
            <x-input-label for="email" value="Email Authorization" class="font-black text-white/40 uppercase tracking-widest text-[10px] pl-2" />
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" 
                class="block w-full bg-dark border border-white/5 focus:border-neon focus:ring-1 focus:ring-neon rounded-2xl p-5 text-white font-bold transition-all placeholder:text-gray-700 outline-none"
                placeholder="NAME@DOMAIN.COM">
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-[10px] font-bold uppercase tracking-widest" />
        </div>

        <!-- Password -->
        <div class="space-y-3">
            <x-input-label for="password" value="New Security Code" class="font-black text-white/40 uppercase tracking-widest text-[10px] pl-2" />
            <input id="password" type="password" name="password" required autocomplete="new-password" 
                class="block w-full bg-dark border border-white/5 focus:border-neon focus:ring-1 focus:ring-neon rounded-2xl p-5 text-white font-bold transition-all placeholder:text-gray-700 outline-none"
                placeholder="MIN. 8 CHARACTERS">
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-[10px] font-bold uppercase tracking-widest" />
        </div>

        <!-- Confirm Password -->
        <div class="space-y-3">
            <x-input-label for="password_confirmation" value="Confirm New Code" class="font-black text-white/40 uppercase tracking-widest text-[10px] pl-2" />
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" 
                class="block w-full bg-dark border border-white/5 focus:border-neon focus:ring-1 focus:ring-neon rounded-2xl p-5 text-white font-bold transition-all placeholder:text-gray-700 outline-none"
                placeholder="REPEAT CODE">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-500 text-[10px] font-bold uppercase tracking-widest" />
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full bg-neon text-dark py-5 rounded-[2rem] font-black text-base uppercase tracking-tighter hover:scale-[1.02] transition shadow-[0_0_30px_rgba(190,242,100,0.3)] flex justify-center items-center group active:scale-95">
                Reset Access Code
                <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </button>
        </div>
    </form>
</x-guest-layout>
