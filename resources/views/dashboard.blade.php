@extends('layouts.public')

@section('title', 'My Bookings')

@section('content')
<div class="py-24 px-4 sm:px-6 lg:px-8 bg-dark min-h-screen relative overflow-hidden">
    <!-- Subtle Background Glows -->
    <div class="absolute top-0 left-0 w-96 h-96 bg-neon/5 rounded-full blur-[120px] -ml-48 -mt-48 pointer-events-none"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-lime-500/5 rounded-full blur-[120px] -mr-48 -mb-48 pointer-events-none"></div>

    <div class="max-w-7xl mx-auto relative z-10">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-8">
            <div class="max-w-xl text-center md:text-left">
                <div class="flex items-center space-x-3 mb-3 justify-center md:justify-start">
                    <div class="h-1 w-8 bg-neon"></div>
                    <span class="text-neon font-black uppercase tracking-[0.25em] text-[10px]">Riwayat Aktivitas</span>
                </div>
                <h3 class="text-4xl font-black text-white italic tracking-tighter uppercase font-heading">
                    PESANAN <span class="text-transparent bg-clip-text bg-gradient-to-r from-neon to-lime-500">ANDA</span>
                </h3>
            </div>
            <div class="flex items-center">
                <a href="{{ route('welcome') }}#courts" class="bg-neon text-dark px-8 py-3.5 rounded-2xl font-black uppercase tracking-wider hover:bg-white transition-all duration-300 shadow-[0_10px_20px_rgba(190,242,100,0.3)] text-sm transform hover:-translate-y-0.5">
                    Pesan Arena Baru
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-8 bg-neon/10 border border-neon/20 text-neon px-6 py-4 rounded-2xl font-bold flex items-center shadow-lg animate-pulse">
                <i class="fas fa-check-circle mr-3 text-lg"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Bookings Table Container -->
        <div class="bg-dark-card/40 backdrop-blur-md rounded-[2rem] border border-white/5 overflow-hidden shadow-2xl relative">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse modern-table">
                    <thead>
                        <tr class="bg-white/[0.02] text-white/40 border-b border-white/5 text-[10px] font-black uppercase tracking-[0.25em]">
                            <th class="px-8 py-6">Arena</th>
                            <th class="px-8 py-6">Jadwal Main</th>
                            <th class="px-8 py-6">Total Biaya</th>
                            <th class="px-8 py-6 text-center">Status</th>
                            <th class="px-8 py-6 text-center">Pembayaran</th>
                            <th class="px-8 py-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/[0.03]">
                        @forelse($bookings as $booking)
                        <tr class="hover:bg-white/[0.015] transition-all duration-300 ease-in-out">
                            <td class="px-8 py-6 font-black text-white italic tracking-tighter uppercase text-lg">
                                <div class="flex items-center space-x-3">
                                    <i class="fas fa-table-tennis-paddle-ball text-neon/60 text-sm"></i>
                                    <span>{{ $booking->court->name }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex flex-col">
                                    <span class="text-white font-bold text-sm">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</span>
                                    <span class="text-white/40 text-[10px] font-black uppercase tracking-widest mt-1.5 flex items-center">
                                        <i class="far fa-clock mr-1.5"></i>
                                        {{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }} WIB
                                    </span>
                                </div>
                            </td>
                            <td class="px-8 py-6 font-black text-neon text-lg italic tracking-tight">
                                Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                            </td>
                            <td class="px-8 py-6 text-center">
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-yellow-500/10 text-yellow-500 border-yellow-500/20',
                                        'approved' => 'bg-neon/10 text-neon border-neon/20',
                                        'cancelled' => 'bg-red-500/10 text-red-500 border-red-500/20',
                                        'expired' => 'bg-white/10 text-white/40 border-white/20',
                                    ];
                                    $statusClass = $statusClasses[$booking->status] ?? 'bg-white/5 text-white/50 border-white/10';
                                @endphp
                                <span class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest border backdrop-blur-sm {{ $statusClass }}">
                                    {{ $booking->status == 'pending' ? 'Tertunda' : ($booking->status == 'approved' ? 'Disetujui' : ($booking->status == 'expired' ? 'Kedaluwarsa' : 'Dibatalkan')) }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-center">
                                @if($booking->payment)
                                    @php
                                        $payStatus = $booking->payment->status;
                                        $payClasses = [
                                            'verified' => 'bg-neon/10 text-neon border-neon/20',
                                            'rejected' => 'bg-red-500/10 text-red-500 border-red-500/20',
                                            'pending' => 'bg-blue-500/10 text-blue-500 border-blue-500/20',
                                            'expired' => 'bg-white/10 text-white/40 border-white/20',
                                        ];
                                        $payClass = $payClasses[$payStatus] ?? 'bg-white/5 text-white/50 border-white/10';
                                    @endphp
                                    <span class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest border backdrop-blur-sm {{ $payClass }}">
                                        {{ $payStatus == 'pending' ? 'Belum Bayar' : ($payStatus == 'verified' ? 'Terverifikasi' : ($payStatus == 'expired' ? 'Kedaluwarsa' : 'Ditolak')) }}
                                    </span>
                                @else
                                    <span class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest border bg-white/5 text-white/20 border-white/10">
                                        N/A
                                    </span>
                                @endif
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex justify-end space-x-2">
                                    @if($booking->status == 'pending')
                                        @if($booking->payment && $booking->payment->snap_token)
                                            <button onclick="payBooking('{{ $booking->payment->snap_token }}')" class="bg-neon text-dark px-4 py-2.5 rounded-xl font-black uppercase text-[9px] tracking-wider hover:bg-white transition-all duration-300 shadow-md">
                                                Bayar Sekarang
                                            </button>
                                        @endif
                                        <a href="{{ route('customer.payments.create', $booking) }}" class="bg-white/5 hover:bg-white/10 text-white px-4 py-2.5 rounded-xl font-black uppercase text-[9px] tracking-wider transition-all duration-300 border border-white/10 flex items-center justify-center" title="Booking Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @else
                                        <div class="flex items-center space-x-2">
                                            @if($booking->status == 'approved' && $booking->payment && $booking->payment->status == 'verified')
                                                <a href="{{ route('bookings.receipt', $booking) }}" class="bg-neon/10 hover:bg-neon text-neon hover:text-dark px-4 py-2.5 rounded-xl font-black uppercase text-[9px] tracking-wider transition-all duration-300 border border-neon/20 flex items-center space-x-2" title="Download Receipt">
                                                    <i class="fas fa-file-pdf"></i>
                                                    <span>Unduh Kuitansi</span>
                                                </a>
                                            @endif
                                            <span class="text-white/20 font-black uppercase text-[9px] tracking-wider italic py-2.5 px-4 border border-white/5 rounded-xl">
                                                {{ $booking->status == 'cancelled' ? 'DIBATALKAN' : 'SELESAI' }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-8 py-24 text-center">
                                <div class="flex flex-col items-center opacity-40">
                                    <i class="fas fa-calendar-times text-5xl mb-6 text-white/30"></i>
                                    <p class="font-black text-white italic text-xl uppercase tracking-tighter">Belum ada arena yang dipesan</p>
                                    <a href="{{ route('welcome') }}#courts" class="mt-8 bg-neon text-dark px-8 py-4 rounded-2xl font-black uppercase tracking-wider hover:bg-white transition shadow-2xl">Cari Lapangan</a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Membership History Section -->
        <div class="mt-24">
            <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-8">
                <div class="max-w-xl text-center md:text-left">
                    <div class="flex items-center space-x-3 mb-3 justify-center md:justify-start">
                        <div class="h-1 w-8 bg-neon"></div>
                        <span class="text-neon font-black uppercase tracking-[0.25em] text-[10px]">Program Membership</span>
                    </div>
                    <h3 class="text-4xl font-black text-white italic tracking-tighter uppercase font-heading">
                        RIWAYAT <span class="text-transparent bg-clip-text bg-gradient-to-r from-neon to-lime-500">MEMBERSHIP</span>
                    </h3>
                </div>
                <div>
                    <a href="{{ route('membership.index') }}" class="text-white/40 hover:text-neon text-[10px] font-black uppercase tracking-[0.2em] transition-colors duration-300 flex items-center space-x-2">
                        <span>Lihat Semua Paket</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Membership Table Container -->
            <div class="bg-dark-card/40 backdrop-blur-md rounded-[2rem] border border-white/5 overflow-hidden shadow-2xl relative">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse modern-table">
                        <thead>
                            <tr class="bg-white/[0.02] text-white/40 border-b border-white/5 text-[10px] font-black uppercase tracking-[0.25em]">
                                <th class="px-8 py-6">Jenis Paket</th>
                                <th class="px-8 py-6">Masa Aktif</th>
                                <th class="px-8 py-6">Harga</th>
                                <th class="px-8 py-6 text-center">Status</th>
                                <th class="px-8 py-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/[0.03]">
                            @forelse($memberships as $membership)
                            <tr class="hover:bg-white/[0.015] transition-all duration-300 ease-in-out">
                                <td class="px-8 py-6 font-black text-white italic tracking-tighter uppercase text-lg">
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-id-card text-neon/60 text-sm"></i>
                                        <span>{{ $membership->tier->name }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex flex-col">
                                        @if($membership->start_date && $membership->end_date)
                                            <span class="text-white font-bold text-sm">{{ \Carbon\Carbon::parse($membership->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($membership->end_date)->format('d M Y') }}</span>
                                            <span class="text-white/40 text-[10px] font-black uppercase tracking-widest mt-1.5 flex items-center">
                                                <i class="far fa-calendar-alt mr-1.5"></i>
                                                {{ $membership->tier->duration_days }} Hari Masa Aktif
                                            </span>
                                        @else
                                            <span class="text-white/40 font-bold italic text-sm">Menunggu Pembayaran</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-8 py-6 font-black text-neon text-lg tracking-tight">
                                    Rp {{ number_format($membership->payment->gross_amount ?? $membership->tier->price, 0, ',', '.') }}
                                </td>
                                <td class="px-8 py-6 text-center">
                                    @php
                                        $mStatusClasses = [
                                            'pending' => 'bg-yellow-500/10 text-yellow-500 border-yellow-500/20',
                                            'active' => 'bg-neon/10 text-neon border-neon/20',
                                            'expired' => 'bg-white/10 text-white/40 border-white/20',
                                            'cancelled' => 'bg-red-500/10 text-red-500 border-red-500/20',
                                        ];
                                        $mStatusClass = $mStatusClasses[$membership->status] ?? 'bg-white/5 text-white/50 border-white/10';
                                    @endphp
                                    <span class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest border backdrop-blur-sm {{ $mStatusClass }}">
                                        {{ $membership->status == 'pending' ? 'Tertunda' : ($membership->status == 'active' ? 'Aktif' : ($membership->status == 'expired' ? 'Kedaluwarsa' : 'Dibatalkan')) }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex justify-end space-x-2">
                                        @if($membership->status == 'active' || ($membership->payment && $membership->payment->status == 'verified'))
                                            <a href="{{ route('memberships.receipt', $membership) }}" class="bg-neon/10 hover:bg-neon text-neon hover:text-dark px-4 py-2.5 rounded-xl font-black uppercase text-[9px] tracking-wider transition-all duration-300 border border-neon/20 flex items-center space-x-2" title="Download Receipt">
                                                <i class="fas fa-file-pdf"></i>
                                                <span>Unduh Kuitansi</span>
                                            </a>
                                        @elseif($membership->status == 'pending' && $membership->payment && $membership->payment->snap_token)
                                            <button onclick="payBooking('{{ $membership->payment->snap_token }}')" class="bg-neon text-dark px-4 py-2.5 rounded-xl font-black uppercase text-[9px] tracking-wider hover:bg-white transition-all duration-300 shadow-lg">
                                                Bayar Sekarang
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-8 py-16 text-center opacity-40 italic font-black uppercase tracking-widest text-xs text-white/30">
                                    Belum ada riwayat membership ditemukan
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <!-- Midtrans Snap JS -->
    <script src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script type="text/javascript">
        function payBooking(snapToken) {
            window.snap.pay(snapToken, {
                onSuccess: function (result) {
                    window.location.reload();
                },
                onPending: function (result) {
                    window.location.reload();
                },
                onError: function (result) {
                    alert("Pembayaran gagal!");
                },
                onClose: function () {
                    console.log('User closed the popup');
                }
            });
        }

        // Auto-popup logic if redirected with ?pay=token
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            const payToken = urlParams.get('pay');
            if (payToken) {
                payBooking(payToken);
            }
        };
    </script>
@endpush
@endsection
