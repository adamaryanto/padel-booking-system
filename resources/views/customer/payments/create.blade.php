@extends('layouts.public')

@section('title', 'Selesaikan Pembayaran')

@section('content')
<div class="py-16 px-4 bg-dark min-h-screen relative overflow-hidden flex items-center justify-center"
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
     }">
    <!-- Subtle Background Glows -->
    <div class="absolute top-0 left-0 w-80 h-80 bg-neon/5 rounded-full blur-[100px] -ml-40 -mt-40 pointer-events-none"></div>
    <div class="absolute bottom-0 right-0 w-80 h-80 bg-lime-500/5 rounded-full blur-[100px] -mr-40 -mb-40 pointer-events-none"></div>

    <div class="max-w-[360px] w-full mx-auto relative z-10 drop-shadow-[0_15px_30px_rgba(0,0,0,0.5)]">
        <!-- Jagged Top Edge -->
        <div class="h-2 w-full bg-repeat-x -mb-[1px]" style="background-image: url('data:image/svg+xml;base64,{{ base64_encode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 8" fill="#1e293b"><polygon points="0,8 12,0 24,8"/></svg>') }}'); background-size: 12px 4px;"></div>
        
        <!-- Receipt Card -->
        <div class="bg-dark-card border-x border-white/5 relative">
            
            <!-- Receipt Header -->
            <div class="p-6 pb-4 text-center border-b border-dashed border-white/10 relative">
                <!-- Tiny Glow -->
                <div class="absolute -top-12 -right-12 w-32 h-32 bg-neon/5 rounded-full blur-2xl pointer-events-none"></div>
                
                <div class="w-12 h-12 bg-neon/10 border border-neon/20 rounded-full flex items-center justify-center mx-auto mb-3 shadow-[0_0_15px_rgba(190,242,100,0.1)]">
                    <i class="fas fa-receipt text-neon text-base"></i>
                </div>
                <h3 class="text-xl font-black text-white italic tracking-tighter uppercase font-heading">
                    STRUK <span class="text-transparent bg-clip-text bg-gradient-to-r from-neon to-lime-500">RESERVASI</span>
                </h3>
                <p class="text-white/40 text-xs font-black uppercase tracking-[0.25em] mt-1.5">PadelHub Indonesia</p>
                
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
                                    setTimeout(() => window.location.reload(), 1000);
                                } else {
                                    const mins = Math.floor(diff / 60000);
                                    const secs = Math.floor((diff % 60000) / 1000);
                                    this.timeLeft = `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
                                }
                            };
                            update();
                            this.interval = setInterval(update, 1000);
                        }
                    }" class="inline-flex items-center space-x-1.5 mt-2.5 bg-yellow-500/10 border border-yellow-500/20 text-yellow-500 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider animate-pulse">
                        <i class="far fa-clock"></i>
                        <span>Batas Waktu: <span x-text="timeLeft">00:00</span></span>
                    </div>
                @endif
            </div>

            <!-- Receipt Body -->
            <div class="p-6 pt-5 space-y-5">
                <!-- Reservation Details -->
                <div class="space-y-3">
                    <div class="flex justify-between items-center text-white/50">
                        <span class="font-bold uppercase tracking-wider text-xs">ID Reservasi</span>
                        <span class="text-white font-black italic text-sm">#{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-white/50">
                        <span class="font-bold uppercase tracking-wider text-xs">Arena</span>
                        <span class="text-white font-black italic uppercase text-sm">{{ $booking->court->name }}</span>
                    </div>
                    <div class="flex justify-between items-center text-white/50">
                        <span class="font-bold uppercase tracking-wider text-xs">Tanggal</span>
                        <span class="text-white font-black italic text-sm">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-white/50">
                        <span class="font-bold uppercase tracking-wider text-xs">Jadwal</span>
                        <span class="text-neon font-black italic tracking-tighter text-sm">{{ substr($booking->start_time, 0, 5) }} – {{ substr($booking->end_time, 0, 5) }} WIB</span>
                    </div>
                    <div class="flex justify-between items-center text-white/50">
                        <span class="font-bold uppercase tracking-wider text-xs">Status</span>
                        @php
                            $statusClasses = [
                                'pending' => 'bg-yellow-500/10 text-yellow-500 border-yellow-500/20',
                                'approved' => 'bg-neon/10 text-neon border-neon/20',
                                'cancelled' => 'bg-red-500/10 text-red-500 border-red-500/20',
                                'expired' => 'bg-white/10 text-white/40 border-white/20',
                                'completed' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
                            ];
                            $statusClass = $statusClasses[$booking->status] ?? 'bg-white/5 text-white/50 border-white/10';
                            $statusLabel = [
                                'pending' => 'Menunggu Pembayaran',
                                'approved' => 'Disetujui',
                                'expired' => 'Kedaluwarsa',
                                'completed' => 'Selesai',
                                'cancelled' => 'Dibatalkan'
                            ][$booking->status] ?? ucfirst($booking->status);
                        @endphp
                        <span class="px-2.5 py-0.5 rounded-full text-[8px] font-black uppercase tracking-wider border backdrop-blur-sm {{ $statusClass }}">
                            {{ $statusLabel }}
                        </span>
                    </div>
                </div>

                <!-- Dotted Divider -->
                <div class="border-t border-dashed border-white/10 my-3"></div>

                <!-- Price Details -->
                <div class="space-y-3">
                    <div class="flex justify-between items-center text-white/50">
                        <span class="font-bold uppercase tracking-wider text-xs">Harga Sewa</span>
                        <span class="text-white font-bold text-sm">Rp {{ number_format($booking->original_price ?: $booking->total_price, 0, ',', '.') }}</span>
                    </div>
                    @if($booking->discount_amount > 0)
                    <div class="flex justify-between items-center text-red-400">
                        <span class="font-bold uppercase tracking-wider text-xs">Diskon Member</span>
                        <span class="font-bold text-sm">-Rp {{ number_format($booking->discount_amount, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between items-center pt-1">
                        <span class="text-white font-black uppercase tracking-wider text-xs">Total Pembayaran</span>
                        <span class="text-neon font-black text-2xl italic">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Dotted Divider -->
                <div class="border-t border-dashed border-white/10 my-3"></div>

                <!-- Simulated Barcode / QR Decor -->
                <div class="flex flex-col items-center justify-center py-0.5 opacity-20 hover:opacity-40 transition duration-300">
                    <div class="flex space-x-[2px] h-5 bg-white/90 p-1 rounded-sm">
                        <!-- Simulated Barcode lines -->
                        <div class="w-[1.5px] h-full bg-dark"></div>
                        <div class="w-[1px] h-full bg-dark"></div>
                        <div class="w-[2px] h-full bg-dark"></div>
                        <div class="w-[1px] h-full bg-dark"></div>
                        <div class="w-[1.5px] h-full bg-dark"></div>
                        <div class="w-[3px] h-full bg-dark"></div>
                        <div class="w-[1px] h-full bg-dark"></div>
                        <div class="w-[1.5px] h-full bg-dark"></div>
                        <div class="w-[1px] h-full bg-dark"></div>
                        <div class="w-[2px] h-full bg-dark"></div>
                        <div class="w-[1.5px] h-full bg-dark"></div>
                        <div class="w-[1px] h-full bg-dark"></div>
                        <div class="w-[3px] h-full bg-dark"></div>
                    </div>
                    <span class="text-[6px] text-white/50 tracking-[0.4em] font-mono mt-1">BOOKING-{{ $booking->id }}</span>
                </div>

                <!-- Action Button -->
                <div class="pt-1 text-center space-y-3">
                    @if($booking->status == 'approved' || $booking->status == 'completed' || ($booking->payment && $booking->payment->status == 'verified'))
                        <a href="{{ route('bookings.receipt', $booking) }}" class="w-full bg-neon text-dark py-4 rounded-xl font-black uppercase tracking-wider text-xs hover:bg-white hover:scale-[1.02] transition-all duration-300 shadow-[0_8px_16px_rgba(190,242,100,0.15)] flex items-center justify-center">
                            Download Bukti Pembayaran <i class="fas fa-file-pdf ml-1.5"></i>
                        </a>
                        @if($booking->status != 'completed')
                            <button @click="$dispatch('open-reschedule-modal', { bookingId: {{ $booking->id }}, courtId: {{ $booking->court_id }}, courtName: '{{ addslashes($booking->court->name) }}', originalDate: '{{ $booking->booking_date }}', originalTime: '{{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }}', isMember: {{ Auth::user()->activeMembership() ? 'true' : 'false' }}, bookingWindow: {{ Auth::user()->getAllowedBookingWindow() }} })" class="w-full bg-neon/10 hover:bg-neon text-neon hover:text-dark py-4 rounded-xl font-black uppercase tracking-wider text-xs transition-all duration-300 border border-neon/20 flex items-center justify-center gap-1.5">
                                Reschedule Jadwal <i class="fas fa-calendar-alt"></i>
                            </button>
                        @endif

                    @elseif($booking->status == 'expired')
                        <div class="w-full bg-white/5 text-white/40 border border-white/10 py-4 rounded-xl font-black uppercase tracking-wider text-xs flex items-center justify-center cursor-not-allowed select-none">
                            KEDALUWARSA <i class="fas fa-clock-rotate-left ml-1.5"></i>
                        </div>
                    @elseif($booking->status == 'cancelled')
                        <div class="w-full bg-red-500/10 text-red-500 border border-red-500/20 py-4 rounded-xl font-black uppercase tracking-wider text-xs flex items-center justify-center cursor-not-allowed select-none">
                            DIBATALKAN <i class="fas fa-ban ml-1.5"></i>
                        </div>
                    @elseif($booking->payment && $booking->payment->snap_token)
                        <button id="pay-button" class="w-full bg-neon text-dark py-4 rounded-xl font-black uppercase tracking-wider text-xs hover:bg-white hover:scale-[1.02] transition-all duration-300 shadow-[0_8px_16px_rgba(190,242,100,0.15)] active:scale-95 transform">
                            BAYAR SEKARANG <i class="fas fa-credit-card ml-1.5"></i>
                        </button>
                    @else
                        <div class="bg-blue-500/10 border border-blue-500/20 text-blue-500 p-3.5 rounded-xl font-bold italic uppercase tracking-wider text-xs">
                            <i class="fas fa-spinner fa-spin mr-1.5"></i> Menyiapkan sistem...
                        </div>
                    @endif
                    
                    <a href="{{ route('dashboard') }}" class="inline-block mt-2 text-white/40 font-black uppercase text-xs tracking-widest hover:text-neon transition">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali ke Riwayat
                    </a>
                </div>
            </div>
        </div>

        <!-- Jagged Bottom Edge -->
        <div class="h-2 w-full bg-repeat-x -mt-[1px]" style="background-image: url('data:image/svg+xml;base64,{{ base64_encode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 8" fill="#1e293b"><polygon points="0,0 12,8 24,0"/></svg>') }}'); background-size: 12px 4px;"></div>
    </div>

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

@push('scripts')
    @if($booking->status == 'pending' && $booking->payment && $booking->payment->snap_token)
    <script src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script type="text/javascript">
        var payButton = document.getElementById('pay-button');
        if (payButton) {
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
        }
    </script>
    @endif
@endpush
