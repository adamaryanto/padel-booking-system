@extends('layouts.public')

@section('title', 'Arena Detail')

@section('content')
<div class="py-24 px-4 sm:px-6 lg:px-8 bg-dark min-h-screen">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row gap-12">
            <!-- Court Info -->
            <div class="md:w-2/3">
                <div class="bg-dark-card rounded-[2rem] overflow-hidden border border-white/5 shadow-2xl group">
                    <!-- Image Carousel -->
                    <div class="aspect-video relative overflow-hidden bg-gray-900 border-b border-white/5" x-data="{ 
                        active: 0, 
                        images: [
                            @if($court->photo) '{{ asset('storage/' . $court->photo) }}', @endif
                            @foreach($court->images as $img) '{{ asset('storage/' . $img->path) }}', @endforeach
                        ]
                    }">
                        <template x-for="(image, index) in images" :key="index">
                            <img x-show="active === index" 
                                 x-transition:enter="transition ease-out duration-500"
                                 x-transition:enter-start="opacity-0 transform scale-105"
                                 x-transition:enter-end="opacity-100 transform scale-100"
                                 :src="image" 
                                 class="absolute inset-0 w-full h-full object-cover brightness-75 group-hover:brightness-100 transition duration-700">
                        </template>

                        <div class="absolute inset-0 bg-gradient-to-t from-dark/80 via-transparent to-transparent"></div>
                        
                        <!-- Navigation Arrows -->
                        <template x-if="images.length > 1">
                            <div class="absolute inset-0 flex items-center justify-between px-6 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button @click="active = active === 0 ? images.length - 1 : active - 1" class="bg-white/10 hover:bg-neon hover:text-dark p-3 rounded-full backdrop-blur-md transition-all">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                </button>
                                <button @click="active = active === images.length - 1 ? 0 : active + 1" class="bg-white/10 hover:bg-neon hover:text-dark p-3 rounded-full backdrop-blur-md transition-all">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </div>
                        </template>

                        <!-- Indicators -->
                        <template x-if="images.length > 1">
                            <div class="absolute bottom-12 left-1/2 transform -translate-x-1/2 flex space-x-2">
                                <template x-for="(image, index) in images" :key="index">
                                    <button @click="active = index" 
                                            class="w-2 h-2 rounded-full transition-all duration-300"
                                            :class="active === index ? 'bg-neon w-8' : 'bg-white/30 hover:bg-white/50'"></button>
                                </template>
                            </div>
                        </template>

                        <div class="absolute bottom-8 left-8">
                            <h2 class="text-5xl font-black text-white italic tracking-tighter uppercase font-heading">{{ $court->name }}</h2>
                        </div>

                        @if($court->images->count() == 0 && !$court->photo)
                            <div class="w-full h-full flex items-center justify-center text-dark bg-neon opacity-20 italic font-black text-6xl">PADEL</div>
                        @endif
                    </div>
                    
                    <div class="p-8">
                        <p class="text-gray-400 text-xl mb-12 leading-relaxed font-medium italic">
                            {{ $court->description }}
                        </p>
                        
                        <!-- Basic Info Tiles -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-12">
                            <div class="p-6 bg-dark rounded-2xl border border-white/5 text-center group hover:border-neon/50 transition-colors">
                                <div class="text-neon mb-3 font-black text-xs tracking-widest uppercase">Jam Buka</div>
                                <div class="text-white font-black text-xl italic uppercase font-heading">{{ $court->open_time ? \Carbon\Carbon::parse($court->open_time)->format('H:i') : '06:00' }}</div>
                            </div>
                            <div class="p-6 bg-dark rounded-2xl border border-white/5 text-center group hover:border-neon/50 transition-colors">
                                <div class="text-neon mb-3 font-black text-xs tracking-widest uppercase">Jam Tutup</div>
                                <div class="text-white font-black text-xl italic uppercase font-heading">{{ $court->close_time ? \Carbon\Carbon::parse($court->close_time)->format('H:i') : '22:00' }}</div>
                            </div>
                            <div class="p-6 bg-dark rounded-2xl border border-white/5 text-center group hover:border-neon/50 transition-colors">
                                <div class="text-neon mb-3 font-black text-xs tracking-widest uppercase">Hari Biasa</div>
                                <div class="text-white font-black text-xl italic uppercase font-heading">Rp {{ number_format($court->price_weekday ?: $court->price_per_hour, 0, ',', '.') }}</div>
                            </div>
                            <div class="p-6 bg-dark rounded-2xl border border-white/5 text-center group hover:border-neon/50 transition-colors">
                                <div class="text-neon mb-3 font-black text-xs tracking-widest uppercase">Akhir Pekan</div>
                                <div class="text-white font-black text-xl italic uppercase font-heading">Rp {{ number_format($court->price_weekend ?: $court->price_per_hour, 0, ',', '.') }}</div>
                            </div>
                        </div>

                        @if($court->member_promo)
                        <div class="mb-12 p-6 rounded-3xl bg-neon/10 border border-neon/20 flex items-center space-x-6 relative overflow-hidden group">
                            <div class="absolute top-0 right-0 p-2 transform rotate-12 opacity-10 group-hover:rotate-0 transition-transform">
                                <svg class="w-16 h-16 text-neon" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            </div>
                            <div class="bg-neon text-dark p-3 rounded-2xl">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
                            </div>
                            <div>
                                <h5 class="text-neon font-black uppercase tracking-widest text-[10px]">Promo Eksklusif Member</h5>
                                <p class="text-white font-bold text-lg mb-0 uppercase tracking-tighter italic">{{ $court->member_promo }}</p>
                            </div>
                        </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @if($court->facilities)
                                @foreach(explode(',', $court->facilities) as $facility)
                                <div class="flex items-center space-x-4 p-6 bg-dark rounded-2xl border border-white/5">
                                    <div class="bg-neon/10 p-3 rounded-xl text-neon">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <span class="text-white font-bold uppercase tracking-widest text-xs">{{ trim($facility) }}</span>
                                </div>
                                @endforeach
                            @else
                                <div class="flex items-center space-x-4 p-6 bg-dark rounded-2xl border border-white/5">
                                    <div class="bg-neon/10 p-3 rounded-xl text-neon">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <span class="text-white font-bold uppercase tracking-widest text-xs">Arena Standar WPT</span>
                                </div>
                                <div class="flex items-center space-x-4 p-6 bg-dark rounded-2xl border border-white/5">
                                    <div class="bg-neon/10 p-3 rounded-xl text-neon">
                                        <i class="fas fa-lightbulb"></i>
                                    </div>
                                    <span class="text-white font-bold uppercase tracking-widest text-xs">Pencahayaan Premium</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Form -->
            <div class="md:w-1/3">
                <div class="bg-dark-card rounded-[2rem] border border-white/5 p-8 shadow-2xl sticky top-24">
                    <h3 class="text-white font-black italic text-2xl uppercase tracking-tighter mb-8 font-heading">PESAN <span class="text-neon">SLOT</span></h3>
                    
                    @if($errors->has('error'))
                        <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-500 px-4 py-3 rounded-xl font-bold text-xs uppercase tracking-widest animate-pulse">
                            <i class="fas fa-exclamation-triangle mr-2"></i> {{ $errors->first('error') }}
                        </div>
                    @endif

                    <!-- Slot Preview (Read-only) -->
                    <div x-data="slotPreview()" class="mb-10 bg-dark rounded-[2.5rem] p-6 border border-white/5">
                        <div class="flex justify-between items-center mb-6 px-2">
                             <h5 class="text-[10px] font-black text-white/40 uppercase tracking-[0.2em]">Status Terkini</h5>
                            <div class="flex space-x-3">
                                <div class="flex items-center space-x-1.5">
                                    <div class="w-1.5 h-1.5 rounded-full bg-neon"></div>
                                    <span class="text-[8px] font-black uppercase text-white/30">Tersedia</span>
                                </div>
                                <div class="flex items-center space-x-1.5">
                                    <div class="w-1.5 h-1.5 rounded-full bg-red-500"></div>
                                    <span class="text-[8px] font-black uppercase text-white/30">Terisi</span>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-4 sm:grid-cols-6 gap-2 max-h-[180px] overflow-y-auto pr-2 custom-scrollbar">

                            <template x-for="slot in slots" :key="slot.time">
                                <div class="py-2.5 rounded-xl text-center border transition-all duration-300"
                                     :class="slot.available ? 'border-neon/10 bg-neon/5 text-neon group-hover:bg-neon/10' : 'border-red-500/10 bg-red-500/5 text-red-500 opacity-40'">
                                    <span class="text-[10px] font-black italic" x-text="slot.time"></span>
                                </div>
                            </template>
                        </div>
                        
                        <div x-show="isLoading" class="mt-4 text-center">
                            <div class="inline-block animate-spin rounded-full h-4 w-4 border-2 border-neon border-t-transparent"></div>
                        </div>
                    </div>

                    <form action="{{ route('customer.bookings.store') }}" method="POST" id="bookingFormMain" class="space-y-6">
                        @csrf
                        <input type="hidden" name="court_id" value="{{ $court->id }}">
                        
                        <div class="space-y-2">
                             <label class="block text-[10px] font-black text-white/40 uppercase tracking-widest ml-2">Pilih Tanggal</label>
                            <input type="date" name="booking_date" id="booking_date" min="{{ date('Y-m-d') }}" max="{{ $maxDate }}" class="w-full bg-dark border-transparent rounded-2xl p-4 focus:ring-2 focus:ring-neon text-white font-bold uppercase tracking-tighter" value="{{ old('booking_date', date('Y-m-d')) }}" required>
                             @if(isset($allowedDays))
                             <p class="text-[8px] font-black text-white/20 uppercase tracking-widest mt-1 ml-2">
                                <i class="fas fa-info-circle mr-1"></i> Maksimal {{ $allowedDays }} hari ke depan
                             </p>
                             @endif
                        </div>

                        @php
                            $openHour = (int)(\Carbon\Carbon::parse($court->open_time ?: '06:00')->format('H'));
                            $closeHour = (int)(\Carbon\Carbon::parse($court->close_time ?: '22:00')->format('H'));
                            if ($closeHour === 0) $closeHour = 24;
                        @endphp
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                 <label class="block text-[10px] font-black text-white/40 uppercase tracking-widest ml-2">Mulai</label>
                                <select name="start_time" id="start_time" class="w-full bg-dark border-transparent rounded-2xl p-4 focus:ring-2 focus:ring-neon text-white font-bold uppercase tracking-tighter custom-select" required>
                                    @for($i = $openHour; $i < $closeHour; $i++)
                                        <option value="{{ sprintf('%02d:00', $i) }}" {{ old('start_time') == sprintf('%02d:00', $i) ? 'selected' : '' }}>{{ sprintf('%02d:00', $i) }}:00</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="space-y-2">
                                 <label class="block text-[10px] font-black text-white/40 uppercase tracking-widest ml-2">Durasi</label>
                                <select name="duration" id="duration" class="w-full bg-dark border-transparent rounded-2xl p-4 focus:ring-2 focus:ring-neon text-white font-bold uppercase tracking-tighter custom-select" required>
                                     <option value="1" {{ old('duration') == '1' ? 'selected' : '' }}>1 Jam</option>
                                     <option value="2" {{ old('duration') == '2' ? 'selected' : '' }}>2 Jam</option>
                                     <option value="3" {{ old('duration') == '3' ? 'selected' : '' }}>3 Jam</option>
                                </select>
                            </div>
                        </div>

                        <div class="p-6 rounded-2xl bg-white/5 border border-white/5 flex justify-between items-center mt-8">
                             <span class="text-[10px] font-black text-white/40 uppercase tracking-widest">Total Biaya</span>
                            <h4 class="text-2xl font-black text-neon italic tracking-tighter m-0" id="display-total">Rp {{ number_format($court->price_per_hour, 0, ',', '.') }}</h4>
                        </div>

                        <button type="submit" id="submitBookingBtn" class="w-full bg-neon text-dark py-5 rounded-2xl font-black uppercase tracking-tighter text-xl hover:bg-white transition shadow-2xl active:scale-95 transform">
                             PESAN SEKARANG <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal Overlay -->
<div id="successModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 bg-dark/90 backdrop-blur-sm" x-cloak>
    <div class="bg-dark-card border border-white/10 rounded-[3rem] p-12 max-w-md w-full text-center shadow-[0_0_100px_rgba(190,242,100,0.1)]">
        <div class="mb-8 w-24 h-24 bg-neon rounded-full flex items-center justify-center mx-auto shadow-[0_0_30px_rgba(190,242,100,0.4)] animate-bounce">
            <i class="fas fa-check text-dark text-4xl"></i>
        </div>
        <h3 class="text-4xl font-black text-white italic tracking-tighter uppercase mb-4">BERHASIL!</h3>
        <p class="text-gray-400 font-medium mb-10 leading-relaxed">Arena Anda sudah berhasil dipesan. Konfirmasi sudah kami kirim ke email Anda.</p>
        
        <div class="flex flex-col space-y-4">
            <a id="downloadReceiptBtn" href="#" target="_blank" class="w-full bg-white text-dark py-4 rounded-2xl font-black uppercase tracking-tighter hover:bg-neon transition shadow-xl">
                <i class="fas fa-file-invoice mr-2"></i>Unduh Kuitansi
            </a>
            <a href="{{ route('dashboard') }}" class="text-gray-500 font-black uppercase text-xs tracking-widest hover:text-white transition">
                Lihat Riwayat Pesanan
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
    function slotPreview() {
        return {
            slots: [],
            isLoading: false,
            courtId: '{{ $court->id }}',
            date: '{{ date('Y-m-d') }}',
            init() {
                this.updateSlots();
                $('#booking_date').on('change', (e) => {
                    this.date = e.target.value;
                    this.updateSlots();
                });
            },
            updateSlots() {
                this.isLoading = true;
                fetch(`/api/availability?court_id=${this.courtId}&date=${this.date}`)
                    .then(res => res.json())
                    .then(data => {
                        this.slots = data;
                        this.isLoading = false;
                    });
            }
        }
    }

    $(document).ready(function() {
        const $bookingForm = $('#bookingFormMain');
        const $bookingBtn = $('#submitBookingBtn');
        const originalBtnText = $bookingBtn.text();
        const $durationSelect = $('#duration');
        const $bookingDate = $('#booking_date');
        const $displayTotal = $('#display-total');
        
        const priceDefault = {{ $court->price_per_hour }};
        const priceWeekday = {{ $court->price_weekday ?: 0 }};
        const priceWeekend = {{ $court->price_weekend ?: 0 }};
        
        const $successModal = $('#successModal');
        const $downloadBtn = $('#downloadReceiptBtn');

        function calculateTotal() {
            const dateStr = $bookingDate.val();
            const date = new Date(dateStr);
            const day = date.getDay(); // 0 is Sunday, 6 is Saturday
            const isWeekend = (day === 0 || day === 6);
            
            let currentPrice = priceDefault;
            if (isWeekend && priceWeekend > 0) {
                currentPrice = priceWeekend;
            } else if (!isWeekend && priceWeekday > 0) {
                currentPrice = priceWeekday;
            }
            
            const total = currentPrice * $durationSelect.val();
            $displayTotal.text('Rp ' + new Intl.NumberFormat('id-ID').format(total));
        }

        $durationSelect.on('change', calculateTotal);
        $bookingDate.on('change', calculateTotal);
        
        // Initial calculation
        calculateTotal();

        $bookingForm.on('submit', async function(e) {
            e.preventDefault();
            
            $bookingBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> PROCESSING...');

            const formData = new FormData(this);

            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.message || result.errors?.error?.[0] || 'Something went wrong');
                }

                if (result.snap_token) {
                    window.snap.pay(result.snap_token, {
                        onSuccess: function (midtransResult) {
                            $downloadBtn.attr('href', `/bookings/${result.booking_id}/receipt`);
                            $successModal.removeClass('hidden');
                        },
                        onPending: function (midtransResult) {
                            window.location.href = "{{ route('dashboard') }}?status=pending";
                        },
                        onError: function (midtransResult) {
                            alert("Payment failed!");
                            window.location.href = "{{ route('dashboard') }}";
                        },
                        onClose: function () {
                            alert("Booking saved. Please complete payment in My Bookings.");
                            window.location.href = "{{ route('dashboard') }}";
                        }
                    });
                } else {
                    window.location.href = "{{ route('dashboard') }}?success=true";
                }

            } catch (error) {
                alert(error.message);
            } finally {
                $bookingBtn.prop('disabled', false).html(originalBtnText);
            }
        });
    });
</script>
@endpush
