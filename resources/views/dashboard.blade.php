@extends('layouts.public')

@section('title', 'My Bookings')

@section('content')
<div class="py-24 px-4 sm:px-6 lg:px-8 bg-dark min-h-screen relative overflow-hidden"
     x-data="{ 
         showRescheduleModal: false,
         rescheduleBookingId: null,
         rescheduleCourtId: null,
         rescheduleCourtName: '',
         rescheduleOriginalDate: '',
         rescheduleOriginalTime: '',
         rescheduleDate: '',
         rescheduleTime: '',
         rescheduleSlots: [],
         rescheduleMinDate: '{{ now()->format('Y-m-d') }}',
         rescheduleMaxDate: '',
         isMember: false,
         bookingWindow: 2,
         isLoadingSlots: false,

         openReschedule(detail) {
             this.rescheduleBookingId = detail.bookingId;
             this.rescheduleCourtId = detail.courtId;
             this.rescheduleCourtName = detail.courtName;
             this.rescheduleOriginalDate = detail.originalDate;
             this.rescheduleOriginalTime = detail.originalTime;
             this.isMember = detail.isMember;
             this.bookingWindow = detail.bookingWindow;
             this.rescheduleTime = '';
             this.rescheduleSlots = [];

             if (!this.isMember) {
                 this.rescheduleDate = detail.originalDate;
                 this.rescheduleMinDate = detail.originalDate;
                 this.rescheduleMaxDate = detail.originalDate;
             } else {
                 this.rescheduleDate = detail.originalDate;
                 this.rescheduleMinDate = '{{ now()->format('Y-m-d') }}';
                 const max = new Date();
                 max.setDate(max.getDate() + this.bookingWindow);
                 this.rescheduleMaxDate = max.toISOString().split('T')[0];
             }

             this.showRescheduleModal = true;
             this.fetchSlots();
         },

         fetchSlots() {
             if (!this.rescheduleCourtId || !this.rescheduleDate) return;
             this.isLoadingSlots = true;
             this.rescheduleTime = '';
             fetch(`/api/availability?court_id=${this.rescheduleCourtId}&date=${this.rescheduleDate}`)
                 .then(res => res.json())
                 .then(data => {
                     this.rescheduleSlots = data;
                     this.isLoadingSlots = false;
                 })
                 .catch(err => {
                     console.error(err);
                     this.isLoadingSlots = false;
                 });
         }
     }"
     @open-reschedule-modal.window="openReschedule($event.detail)">
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

        <!-- Bookings Table Container (Desktop Only) -->
        <div class="hidden md:block bg-dark-card/40 backdrop-blur-md rounded-[2rem] border border-white/5 overflow-hidden shadow-2xl relative">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse modern-table">
                    <thead>
                        <tr class="bg-white/[0.02] text-white/40 border-b border-white/5 text-[10px] font-black uppercase tracking-[0.25em]">
                            <th class="px-4 py-4">Arena</th>
                            <th class="px-4 py-4">Jadwal Main</th>
                            <th class="px-4 py-4">Total Biaya</th>
                            <th class="px-4 py-4 text-center">Status</th>
                            <th class="px-4 py-4 text-center">Pembayaran</th>
                            <th class="px-4 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/[0.03]">
                        @forelse($bookings as $booking)
                        <tr class="hover:bg-white/[0.015] transition-all duration-300 ease-in-out">
                            <td class="px-4 py-4 font-black text-white italic tracking-tighter uppercase text-sm align-top whitespace-nowrap">
                                <div class="flex items-center space-x-3">
                                    <i class="fas fa-table-tennis-paddle-ball text-neon/60 text-sm"></i>
                                    <span>{{ $booking->court->name }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-4 align-top whitespace-nowrap">
                                <div class="flex flex-col">
                                    <span class="text-white font-bold text-xs">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</span>
                                    <span class="text-white/40 text-[9px] font-black uppercase tracking-widest mt-1.5 flex items-center">
                                        <i class="far fa-clock mr-1.5"></i>
                                        {{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }} WIB
                                    </span>
                                </div>
                            </td>
                            <td class="px-4 py-4 font-black text-neon text-sm italic tracking-tight align-top whitespace-nowrap">
                                Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-4 text-center align-top whitespace-nowrap">
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-yellow-500/10 text-yellow-500 border-yellow-500/20',
                                        'approved' => 'bg-neon/10 text-neon border-neon/20',
                                        'cancelled' => 'bg-red-500/10 text-red-500 border-red-500/20',
                                        'expired' => 'bg-white/10 text-white/40 border-white/20',
                                        'completed' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
                                    ];
                                    $statusClass = $statusClasses[$booking->status] ?? 'bg-white/5 text-white/50 border-white/10';
                                @endphp
                                <span class="px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-wider border backdrop-blur-sm {{ $statusClass }}">
                                    {{ $booking->status == 'pending' ? 'Menunggu Pembayaran' : ($booking->status == 'approved' ? 'Disetujui' : ($booking->status == 'completed' ? 'Selesai' : ($booking->status == 'expired' ? 'Kedaluwarsa' : 'Dibatalkan'))) }}
                                </span>
                                @if($booking->status == 'pending')
                                    <div class="mt-2 text-[9px] opacity-0 flex items-center justify-center space-x-1 select-none pointer-events-none">
                                        <i class="far fa-clock mr-0.5"></i>
                                        <span>00:00</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-center align-top whitespace-nowrap">
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
                                    <span class="px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-wider border backdrop-blur-sm {{ $payClass }}">
                                        {{ $payStatus == 'pending' ? 'Menunggu Pembayaran' : ($payStatus == 'verified' ? 'Terverifikasi' : ($payStatus == 'expired' ? 'Kedaluwarsa' : 'Ditolak')) }}
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-wider border bg-white/5 text-white/20 border-white/10">
                                        N/A
                                    </span>
                                @endif
 
                                @if($booking->status == 'pending')
                                    <div x-data="{
                                        timeLeft: '',
                                        interval: null,
                                        createdAt: '{{ $booking->created_at->toIso8601String() }}',
                                        init() {
                                            const update = () => {
                                                const created = new Date(this.createdAt).getTime();
                                                const expires = created + (10 * 60 * 1000);
                                                const now = new Date().getTime();
                                                const diff = expires - now;
                                                if (diff <= 0) {
                                                    this.timeLeft = 'Kedaluwarsa';
                                                    clearInterval(this.interval);
                                                    setTimeout(() => window.location.reload(), 1500);
                                                } else {
                                                    const mins = Math.floor(diff / 60000);
                                                    const secs = Math.floor((diff % 60000) / 1000);
                                                    this.timeLeft = `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
                                                }
                                            };
                                            update();
                                            this.interval = setInterval(update, 1000);
                                        }
                                    }" class="mt-2 text-[9px] text-yellow-500 font-bold flex items-center justify-center space-x-1">
                                        <i class="far fa-clock animate-pulse mr-0.5"></i>
                                        <span x-text="timeLeft">00:00</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-right align-top whitespace-nowrap">
                                <div class="flex justify-end space-x-2">
                                    @if($booking->status == 'pending')
                                        @if($booking->payment && $booking->payment->snap_token)
                                            <a href="{{ route('customer.payments.create', $booking) }}" class="bg-neon text-dark px-3 py-2 rounded-xl font-black uppercase text-[8px] tracking-wider hover:bg-white transition-all duration-300 shadow-md inline-block">
                                                Bayar Sekarang
                                            </a>
                                        @endif
                                    @elseif($booking->status == 'approved' && $booking->payment && $booking->payment->status == 'verified')
                                        <a href="{{ route('bookings.receipt', $booking) }}" class="bg-neon/10 hover:bg-neon text-neon hover:text-dark px-3 py-2 rounded-xl font-black uppercase text-[8px] tracking-wider transition-all duration-300 border border-neon/20 flex items-center space-x-2" title="Download Receipt">
                                            <i class="fas fa-file-pdf"></i>
                                            <span>Unduh Kuitansi</span>
                                        </a>
                                        
                                        <!-- Reschedule Button -->
                                        <button @click="$dispatch('open-reschedule-modal', { bookingId: {{ $booking->id }}, courtId: {{ $booking->court_id }}, courtName: '{{ addslashes($booking->court->name) }}', originalDate: '{{ $booking->booking_date }}', originalTime: '{{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }}', isMember: {{ Auth::user()->activeMembership() ? 'true' : 'false' }}, bookingWindow: {{ Auth::user()->getAllowedBookingWindow() }} })" class="bg-neon/10 hover:bg-neon text-neon hover:text-dark px-3 py-2 rounded-xl font-black uppercase text-[8px] tracking-wider transition-all duration-300 border border-neon/20 flex items-center space-x-2" title="Reschedule Jadwal">
                                            <i class="fas fa-calendar-alt"></i>
                                            <span>Reschedule</span>
                                        </button>
                                    @endif
 
                                    <!-- Detail Link -->
                                    <a href="{{ route('customer.payments.create', $booking) }}" class="bg-white/5 hover:bg-white/10 text-white px-3 py-2 rounded-xl font-black uppercase text-[8px] tracking-wider transition-all duration-300 border border-white/10 flex items-center justify-center" title="Booking Detail">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>
 
                                    @if($booking->status != 'pending')
                                        @php
                                            $badgeClasses = [
                                                'approved' => 'text-neon/40 border-neon/10',
                                                'cancelled' => 'text-red-500/40 border-red-500/10',
                                                'expired' => 'text-white/20 border-white/5',
                                                'completed' => 'text-emerald-500/40 border-emerald-500/10',
                                            ];
                                            $badgeText = [
                                                'approved' => 'DISETUJUI',
                                                'cancelled' => 'DIBATALKAN',
                                                'expired' => 'KEDALUWARSA',
                                                'completed' => 'SELESAI',
                                            ];
                                            $badgeClass = $badgeClasses[$booking->status] ?? 'text-white/20 border-white/5';
                                            $text = $badgeText[$booking->status] ?? 'SELESAI';
                                        @endphp
                                        <span class="font-black uppercase text-[8px] tracking-wider italic py-2 px-3 border rounded-xl flex items-center justify-center {{ $badgeClass }}">
                                            {{ $text }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-24 text-center">
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
 
        <!-- Bookings Mobile List (Mobile Only) -->
        <div class="block md:hidden space-y-4">
            @forelse($bookings as $booking)
            <div class="bg-dark-card/40 backdrop-blur-md rounded-3xl border border-white/5 p-5 shadow-lg relative">
                <!-- Top Header: Court & Statuses -->
                <div class="flex justify-between items-start gap-2">
                    <div class="flex items-center space-x-2 text-white font-bold italic uppercase text-xs">
                        <i class="fas fa-table-tennis-paddle-ball text-neon"></i>
                        <span class="truncate max-w-[150px]">{{ $booking->court->name }}</span>
                    </div>
                    <div class="flex flex-col items-end space-y-1.5 shrink-0">
                        @php
                            $statusClasses = [
                                'pending' => 'bg-yellow-500/10 text-yellow-500 border-yellow-500/20',
                                'approved' => 'bg-neon/10 text-neon border-neon/20',
                                'cancelled' => 'bg-red-500/10 text-red-500 border-red-500/20',
                                'expired' => 'bg-white/10 text-white/40 border-white/20',
                                'completed' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
                            ];
                            $statusClass = $statusClasses[$booking->status] ?? 'bg-white/5 text-white/50 border-white/10';
                        @endphp
                        <span class="px-2.5 py-1 rounded-full text-[8px] font-black uppercase tracking-wider border backdrop-blur-sm {{ $statusClass }}">
                            {{ $booking->status == 'pending' ? 'Menunggu Pembayaran' : ($booking->status == 'approved' ? 'Disetujui' : ($booking->status == 'completed' ? 'Selesai' : ($booking->status == 'expired' ? 'Kedaluwarsa' : 'Dibatalkan'))) }}
                        </span>
 
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
                            <span class="px-2 py-0.5 rounded-full text-[7px] font-black uppercase tracking-wider border backdrop-blur-sm {{ $payClass }}">
                                Bayar: {{ $payStatus == 'pending' ? 'Pending' : ($payStatus == 'verified' ? 'Terverifikasi' : ($payStatus == 'expired' ? 'Kedaluwarsa' : 'Ditolak')) }}
                            </span>
                        @else
                            <span class="px-2 py-0.5 rounded-full text-[7px] font-black uppercase tracking-wider border bg-white/5 text-white/20 border-white/10">
                                Bayar: N/A
                            </span>
                        @endif
                    </div>
                </div>
 
                <!-- Info Details: Time & Cost -->
                <div class="mt-4 grid grid-cols-2 gap-4 border-t border-b border-white/5 py-3">
                    <div class="flex flex-col">
                        <span class="text-white/40 text-[8px] font-bold uppercase tracking-wider mb-1">Jadwal Main</span>
                        <span class="text-white font-bold text-[11px]">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</span>
                        <span class="text-white/60 text-[9px] mt-0.5 font-bold">{{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }} WIB</span>
                    </div>
                    <div class="flex flex-col items-end justify-center">
                        <span class="text-white/40 text-[8px] font-bold uppercase tracking-wider mb-1">Total Biaya</span>
                        <span class="font-black text-neon italic text-sm">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                    </div>
                </div>
 
                <!-- Footer: Timer & Actions -->
                <div class="mt-3 flex justify-between items-center gap-2">
                    <!-- Left: Timer (if pending) -->
                    <div>
                        @if($booking->status == 'pending')
                            <div x-data="{
                                timeLeft: '',
                                interval: null,
                                createdAt: '{{ $booking->created_at->toIso8601String() }}',
                                init() {
                                    const update = () => {
                                        const created = new Date(this.createdAt).getTime();
                                        const expires = created + (10 * 60 * 1000);
                                        const now = new Date().getTime();
                                        const diff = expires - now;
                                        if (diff <= 0) {
                                            this.timeLeft = 'Kedaluwarsa';
                                            clearInterval(this.interval);
                                            setTimeout(() => window.location.reload(), 1500);
                                        } else {
                                            const mins = Math.floor(diff / 60000);
                                            const secs = Math.floor((diff % 60000) / 1000);
                                            this.timeLeft = `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
                                        }
                                    };
                                    update();
                                    this.interval = setInterval(update, 1000);
                                }
                            }" class="text-[9px] text-yellow-500 font-bold flex items-center space-x-1">
                                <i class="far fa-clock animate-pulse mr-0.5"></i>
                                <span x-text="timeLeft">00:00</span>
                            </div>
                        @endif
                    </div>
 
                    <!-- Right: Actions -->
                    <div class="flex items-center space-x-2">
                        @if($booking->status == 'pending')
                            @if($booking->payment && $booking->payment->snap_token)
                                <a href="{{ route('customer.payments.create', $booking) }}" class="bg-neon text-dark px-3 py-1.5 rounded-lg font-black uppercase text-[8px] tracking-wider hover:bg-white transition-all duration-300">
                                    Bayar
                                </a>
                            @endif
                        @elseif($booking->status == 'approved' && $booking->payment && $booking->payment->status == 'verified')
                            <a href="{{ route('bookings.receipt', $booking) }}" class="bg-neon/10 text-neon px-2 py-1.5 rounded-lg border border-neon/20 hover:bg-neon hover:text-dark transition text-[8px]" title="Unduh Kuitansi">
                                <i class="fas fa-file-pdf"></i>
                            </a>
                            <button @click="$dispatch('open-reschedule-modal', { bookingId: {{ $booking->id }}, courtId: {{ $booking->court_id }}, courtName: '{{ addslashes($booking->court->name) }}', originalDate: '{{ $booking->booking_date }}', originalTime: '{{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }}', isMember: {{ Auth::user()->activeMembership() ? 'true' : 'false' }}, bookingWindow: {{ Auth::user()->getAllowedBookingWindow() }} })" class="bg-neon/10 text-neon px-2 py-1.5 rounded-lg border border-neon/20 hover:bg-neon hover:text-dark transition text-[8px]" title="Reschedule Jadwal">
                                <i class="fas fa-calendar-alt"></i>
                            </button>
                        @endif
 
                        <a href="{{ route('customer.payments.create', $booking) }}" class="bg-white/5 hover:bg-white/10 text-white px-2 py-1.5 rounded-lg border border-white/10 transition text-[8px]" title="Booking Detail">
                            <i class="fas fa-eye text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-dark-card/40 backdrop-blur-md rounded-3xl border border-white/5 p-8 text-center opacity-40">
                <i class="fas fa-calendar-times text-3xl mb-4 text-white/30"></i>
                <p class="font-black text-white italic text-sm uppercase tracking-tighter">Belum ada arena yang dipesan</p>
                <a href="{{ route('welcome') }}#courts" class="mt-4 inline-block bg-neon text-dark px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-wider">Cari Lapangan</a>
            </div>
            @endforelse
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

            <!-- Membership Table Container (Desktop Only) -->
            <div class="hidden md:block bg-dark-card/40 backdrop-blur-md rounded-[2rem] border border-white/5 overflow-hidden shadow-2xl relative">
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
                                        {{ $membership->status == 'pending' ? 'Menunggu Pembayaran' : ($membership->status == 'active' ? 'Aktif' : ($membership->status == 'expired' ? 'Kedaluwarsa' : 'Dibatalkan')) }}
                                    </span>

                                    @if($membership->status == 'pending')
                                        <div x-data="{
                                            timeLeft: '',
                                            interval: null,
                                            createdAt: '{{ $membership->created_at->toIso8601String() }}',
                                            init() {
                                                const update = () => {
                                                    const created = new Date(this.createdAt).getTime();
                                                    const expires = created + (10 * 60 * 1000);
                                                    const now = new Date().getTime();
                                                    const diff = expires - now;
                                                    if (diff <= 0) {
                                                        this.timeLeft = 'Kedaluwarsa';
                                                        clearInterval(this.interval);
                                                        setTimeout(() => window.location.reload(), 1500);
                                                    } else {
                                                        const mins = Math.floor(diff / 60000);
                                                        const secs = Math.floor((diff % 60000) / 1000);
                                                        this.timeLeft = `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
                                                    }
                                                };
                                                update();
                                                this.interval = setInterval(update, 1000);
                                            }
                                        }" class="mt-2 text-[10px] text-yellow-500 font-bold flex items-center justify-center space-x-1">
                                            <i class="far fa-clock animate-pulse mr-0.5"></i>
                                            <span x-text="timeLeft">00:00</span>
                                        </div>
                                    @endif
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
 
            <!-- Membership Mobile List (Mobile Only) -->
            <div class="block md:hidden space-y-4">
                @forelse($memberships as $membership)
                <div class="bg-dark-card/40 backdrop-blur-md rounded-3xl border border-white/5 p-5 shadow-lg relative">
                    <!-- Top Header: Paket & Status -->
                    <div class="flex justify-between items-start gap-2">
                        <div class="flex items-center space-x-2 text-white font-bold italic uppercase text-xs">
                            <i class="fas fa-id-card text-neon"></i>
                            <span>{{ $membership->tier->name }}</span>
                        </div>
                        <div>
                            @php
                                $mStatusClasses = [
                                    'pending' => 'bg-yellow-500/10 text-yellow-500 border-yellow-500/20',
                                    'active' => 'bg-neon/10 text-neon border-neon/20',
                                    'expired' => 'bg-white/10 text-white/40 border-white/20',
                                    'cancelled' => 'bg-red-500/10 text-red-500 border-red-500/20',
                                ];
                                $mStatusClass = $mStatusClasses[$membership->status] ?? 'bg-white/5 text-white/50 border-white/10';
                            @endphp
                            <span class="px-2 py-0.5 rounded-full text-[8px] font-black uppercase tracking-widest border backdrop-blur-sm {{ $mStatusClass }}">
                                {{ $membership->status == 'pending' ? 'Menunggu Pembayaran' : ($membership->status == 'active' ? 'Aktif' : ($membership->status == 'expired' ? 'Kedaluwarsa' : 'Dibatalkan')) }}
                            </span>
                        </div>
                    </div>
 
                    <!-- Info Details: Duration & Price -->
                    <div class="mt-4 grid grid-cols-2 gap-4 border-t border-b border-white/5 py-3">
                        <div class="flex flex-col">
                            <span class="text-white/40 text-[8px] font-bold uppercase tracking-wider mb-1">Masa Aktif</span>
                            @if($membership->start_date && $membership->end_date)
                                <span class="text-white font-bold text-[10px]">{{ \Carbon\Carbon::parse($membership->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($membership->end_date)->format('d M Y') }}</span>
                                <span class="text-white/40 text-[8px] font-black uppercase tracking-widest mt-0.5">
                                    {{ $membership->tier->duration_days }} Hari
                                </span>
                            @else
                                <span class="text-white/40 font-bold italic text-[11px]">Menunggu Pembayaran</span>
                            @endif
                        </div>
                        <div class="flex flex-col items-end justify-center">
                            <span class="text-white/40 text-[8px] font-bold uppercase tracking-wider mb-1">Harga</span>
                            <span class="font-black text-neon italic text-sm">Rp {{ number_format($membership->payment->gross_amount ?? $membership->tier->price, 0, ',', '.') }}</span>
                        </div>
                    </div>
 
                    <!-- Footer: Timer & Actions -->
                    <div class="mt-3 flex justify-between items-center gap-2">
                        <!-- Left: Timer -->
                        <div>
                            @if($membership->status == 'pending')
                                <div x-data="{
                                    timeLeft: '',
                                    interval: null,
                                    createdAt: '{{ $membership->created_at->toIso8601String() }}',
                                    init() {
                                        const update = () => {
                                            const created = new Date(this.createdAt).getTime();
                                            const expires = created + (10 * 60 * 1000);
                                            const now = new Date().getTime();
                                            const diff = expires - now;
                                            if (diff <= 0) {
                                                this.timeLeft = 'Kedaluwarsa';
                                                clearInterval(this.interval);
                                                setTimeout(() => window.location.reload(), 1500);
                                            } else {
                                                const mins = Math.floor(diff / 60000);
                                                const secs = Math.floor((diff % 60000) / 1000);
                                                this.timeLeft = `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
                                            }
                                        };
                                        update();
                                        this.interval = setInterval(update, 1000);
                                    }
                                }" class="text-[9px] text-yellow-500 font-bold flex items-center space-x-1">
                                    <i class="far fa-clock animate-pulse mr-0.5"></i>
                                    <span x-text="timeLeft">00:00</span>
                                </div>
                            @endif
                        </div>
 
                        <!-- Right: Action -->
                        <div>
                            @if($membership->status == 'active' || ($membership->payment && $membership->payment->status == 'verified'))
                                <a href="{{ route('memberships.receipt', $membership) }}" class="bg-neon/10 hover:bg-neon text-neon hover:text-dark px-3 py-1.5 rounded-lg font-black uppercase text-[8px] tracking-wider transition-all duration-300 border border-neon/20 flex items-center space-x-1" title="Download Receipt">
                                    <i class="fas fa-file-pdf"></i>
                                    <span>Kuitansi</span>
                                </a>
                            @elseif($membership->status == 'pending' && $membership->payment && $membership->payment->snap_token)
                                <button onclick="payBooking('{{ $membership->payment->snap_token }}')" class="bg-neon text-dark px-3 py-1.5 rounded-lg font-black uppercase text-[8px] tracking-wider hover:bg-white transition-all duration-300 shadow-md">
                                    Bayar
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="bg-dark-card/40 backdrop-blur-md rounded-3xl border border-white/5 p-8 text-center opacity-40 italic font-black uppercase tracking-widest text-xs text-white/30">
                    Belum ada riwayat membership ditemukan
                </div>
                @endforelse
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
        }

        // Auto-popup & URL cleaning logic
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            const payToken = urlParams.get('pay');
            const successParam = urlParams.get('success');
            const statusParam = urlParams.get('status');

            // Handle success alert
            if (successParam === 'true') {
                Swal.fire({
                    icon: 'success',
                    title: 'Pembayaran Berhasil!',
                    text: 'Terima kasih, pembayaran Anda telah berhasil kami terima.',
                    background: '#1e293b',
                    color: '#fff',
                    confirmButtonColor: '#bef264',
                    customClass: {
                        popup: 'rounded-[2rem] border border-white/5 shadow-2xl'
                    }
                });
            }

            // Handle pending alert
            if (statusParam === 'pending') {
                Swal.fire({
                    icon: 'info',
                    title: 'Pembayaran Diproses',
                    text: 'Pembayaran Anda sedang diproses. Silakan cek status beberapa saat lagi.',
                    background: '#1e293b',
                    color: '#fff',
                    confirmButtonColor: '#bef264',
                    customClass: {
                        popup: 'rounded-[2rem] border border-white/5 shadow-2xl'
                    }
                });
            }

            // Auto-popup payment modal if payToken is present
            if (payToken) {
                payBooking(payToken);
            }

            // Clean query parameters from URL so they don't trigger alerts again on page refresh
            if (payToken || successParam || statusParam) {
                if (window.history.replaceState) {
                    const cleanUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
                    window.history.replaceState({ path: cleanUrl }, '', cleanUrl);
                }
            }
        };
    </script>
@endpush
        <!-- Reschedule Booking Modal -->
        <div x-show="showRescheduleModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity" @click="showRescheduleModal = false"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div class="relative transform overflow-hidden rounded-[2rem] bg-dark-card border border-white/10 p-8 text-left shadow-2xl transition-all w-full max-w-lg"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-black text-white italic tracking-tighter uppercase">
                            RESCHEDULE <span class="text-transparent bg-clip-text bg-gradient-to-r from-neon to-lime-500">JADWAL</span>
                        </h3>
                        <button @click="showRescheduleModal = false" class="text-white/40 hover:text-white transition">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <!-- Info Box showing original booking details -->
                    <div class="bg-white/5 border border-white/5 rounded-2xl p-4 mb-6 text-xs space-y-2">
                        <div class="flex justify-between">
                            <span class="text-white/40 font-bold uppercase tracking-wider">Arena:</span>
                            <span class="text-white font-bold" x-text="rescheduleCourtName"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-white/40 font-bold uppercase tracking-wider">Jadwal Awal:</span>
                            <span class="text-neon font-black italic" x-text="rescheduleOriginalDate + ' | ' + rescheduleOriginalTime"></span>
                        </div>
                    </div>

                    <form :action="'{{ url('/bookings') }}/' + rescheduleBookingId + '/reschedule'" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label class="block text-[10px] font-black text-white/40 uppercase tracking-[0.2em] mb-2">Pilih Tanggal Baru</label>
                            <input type="date" name="booking_date" x-model="rescheduleDate" @change="fetchSlots()" required
                                   :min="rescheduleMinDate" :max="rescheduleMaxDate" :readonly="!isMember"
                                   class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-neon focus:ring-1 focus:ring-neon outline-none transition text-sm"
                                   :class="{'opacity-50 cursor-not-allowed': !isMember}">
                            <template x-if="!isMember">
                                <span class="text-[9px] text-yellow-500 font-bold mt-1.5 block">
                                    <i class="fas fa-info-circle mr-1"></i> Sebagai non-member, Anda hanya dapat mengganti jam di hari yang sama.
                                </span>
                            </template>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-white/40 uppercase tracking-[0.2em] mb-2">Pilih Jam Baru</label>
                            <select name="start_time" x-model="rescheduleTime" required
                                    class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-neon focus:ring-1 focus:ring-neon outline-none transition text-sm"
                                    :disabled="isLoadingSlots">
                                <option value="" class="bg-dark text-white">-- Pilih Jam --</option>
                                <template x-for="slot in rescheduleSlots" :key="slot.time">
                                    <option :value="slot.time" class="bg-dark text-white" :disabled="!slot.available"
                                            x-text="slot.time + (!slot.available ? ' (Penuh)' : '')"></option>
                                </template>
                            </select>
                            <template x-if="isLoadingSlots">
                                <span class="text-[9px] text-neon font-bold mt-1.5 block">
                                    <i class="fas fa-spinner fa-spin mr-1"></i> Memuat jadwal kosong...
                                </span>
                            </template>
                        </div>

                        <div class="flex space-x-3 justify-end pt-4">
                            <button type="button" @click="showRescheduleModal = false"
                                    class="px-6 py-3 bg-white/5 hover:bg-white/10 border border-white/10 text-white rounded-xl font-bold uppercase tracking-wider text-xs transition">
                                Batal
                            </button>
                            <button type="submit" :disabled="!rescheduleTime || isLoadingSlots"
                                    class="px-6 py-3 bg-neon hover:bg-white text-dark disabled:opacity-40 disabled:hover:bg-neon disabled:cursor-not-allowed rounded-xl font-black uppercase tracking-wider text-xs transition shadow-lg shadow-neon/20">
                                Konfirmasi Reschedule
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


</div>
@endsection
