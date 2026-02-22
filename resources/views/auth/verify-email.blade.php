<x-guest-layout>
    <div class="mb-10 text-center">
        <h2 class="text-3xl font-black text-white italic uppercase tracking-tighter font-heading">VERIFY <span class="text-neon">STATUS</span></h2>
        <p class="text-gray-500 mt-2 font-bold uppercase tracking-widest text-[9px]">Validation bridge for elite athletes</p>
    </div>

    <div class="bg-neon/5 border border-neon/10 rounded-2xl p-6 mb-10">
        <p class="text-[11px] font-medium text-gray-400 leading-relaxed text-center uppercase tracking-wider">
            {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success bg-neon/10 border border-neon/20 text-neon p-4 rounded-xl text-[10px] font-bold uppercase tracking-widest mb-10 text-center">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="flex flex-col space-y-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="w-full bg-neon text-dark py-4 rounded-2xl font-black uppercase tracking-tighter hover:scale-[1.02] transition shadow-lg active:scale-95 text-sm">
                {{ __('Resend Link') }}
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="text-center">
            @csrf
            <button type="submit" class="text-[10px] font-black text-gray-500 uppercase tracking-widest hover:text-white transition">
                {{ __('Abort & Logout') }}
            </button>
        </form>
    </div>
</x-guest-layout>
