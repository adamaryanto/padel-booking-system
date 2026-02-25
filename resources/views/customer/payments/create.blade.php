@extends('layouts.public')

@section('title', 'Selesaikan Pembayaran')

@section('content')
<div class="py-24 px-4 sm:px-6 lg:px-8 bg-dark min-h-screen">
    <div class="max-w-2xl mx-auto">
        <div class="bg-dark-card rounded-[3rem] border border-white/5 overflow-hidden shadow-2xl relative">
            <!-- Neon Header -->
            <div class="bg-gradient-to-br from-neon/20 to-transparent p-12 text-center border-b border-white/5 relative overflow-hidden">
                <div class="absolute -top-12 -right-12 opacity-10 blur-2xl flex">
                    <i class="fas fa-credit-card text-[15rem] text-neon"></i>
                </div>
                <div class="relative z-10">
                    <div class="w-24 h-24 bg-neon/10 border border-neon/20 rounded-full flex items-center justify-center mx-auto mb-6 shadow-[0_0_30px_rgba(190,242,100,0.1)]">
                        <i class="fas fa-receipt text-neon text-3xl"></i>
                    </div>
                    <h2 class="text-4xl font-black text-white italic tracking-tighter uppercase mb-2">PEMBAYARAN <span class="text-neon">AMAN</span></h2>
                    <p class="text-gray-500 font-black uppercase text-[10px] tracking-[.3em]">Terenkripsi via Keamanan Midtrans</p>
                </div>
            </div>

            <div class="p-12 relative">
                <!-- Floating Total -->
                <div class="absolute -top-10 left-1/2 -translate-x-1/2 bg-white rounded-3xl p-6 shadow-2xl min-w-[280px] text-center transform hover:scale-105 transition duration-500">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">Total Akhir</span>
                    <h2 class="text-3xl font-black text-dark tracking-tighter italic uppercase">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</h2>
                </div>

                <div class="mt-12 space-y-6">
                    <div class="bg-dark border border-white/5 rounded-3xl p-8 space-y-4">
                        <div class="flex justify-between items-center pb-4 border-b border-white/5">
                            <span class="text-[10px] font-black text-white/40 uppercase tracking-widest">ID Reservasi</span>
                            <span class="text-white font-black italic">#{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="flex justify-between items-center pb-4 border-b border-white/5">
                            <span class="text-[10px] font-black text-white/40 uppercase tracking-widest">Arena</span>
                            <span class="text-white font-black italic uppercase">{{ $booking->court->name }}</span>
                        </div>
                        <div class="flex justify-between items-center pb-4 border-b border-white/5">
                            <span class="text-[10px] font-black text-white/40 uppercase tracking-widest">Tanggal</span>
                            <span class="text-white font-black italic">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center pt-2">
                            <span class="text-[10px] font-black text-white/40 uppercase tracking-widest">Durasi</span>
                            <span class="text-neon font-black italic tracking-tighter">{{ substr($booking->start_time, 0, 5) }} – {{ substr($booking->end_time, 0, 5) }} WIB</span>
                        </div>
                    </div>

                    <div class="pt-8 text-center">
                        @if($booking->payment && $booking->payment->snap_token)
                            <button id="pay-button" class="w-full bg-neon text-dark py-6 rounded-2xl font-black uppercase tracking-tighter text-2xl hover:bg-white transition shadow-2xl active:scale-95 transform">
                                BAYAR SEKARANG <i class="fas fa-credit-card ml-3"></i>
                            </button>
                        @else
                            <div class="bg-blue-500/10 border border-blue-500/20 text-blue-500 p-6 rounded-2xl font-bold italic uppercase tracking-widest text-xs">
                                <i class="fas fa-spinner fa-spin mr-3"></i> Menyiapkan sistem...
                            </div>
                        @endif
                        
                        <a href="{{ route('dashboard') }}" class="inline-block mt-8 text-gray-500 font-black uppercase text-xs tracking-widest hover:text-white transition">
                            <i class="fas fa-arrow-left mr-2 text-neon"></i> Kembali ke Riwayat
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @if($booking->payment && $booking->payment->snap_token)
    <script src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script type="text/javascript">
        var payButton = document.getElementById('pay-button');
        payButton.addEventListener('click', function () {
            window.snap.pay('{{ $booking->payment->snap_token }}', {
                onSuccess: function (result) {
                    window.location.href = "{{ route('dashboard') }}?success=true";
                },
                onPending: function (result) {
                    window.location.href = "{{ route('dashboard') }}?status=pending";
                },
                onError: function (result) {
                    alert("Pembayaran gagal!");
                },
                onClose: function () {
                    console.log('User closed the popup');
                }
            });
        });
    </script>
    @endif
@endpush
