@extends('layouts.public')

@section('title', 'Membership')

@section('content')
<div class="py-24 px-4 sm:px-6 lg:px-8 bg-dark min-h-screen" x-data="membershipApp()" x-init="if(isPolling) checkStatus()">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16">
            <h2 class="text-neon font-black uppercase tracking-[.3em] text-xs mb-4">Tingkatkan Performa Anda</h2>
            <h3 class="text-5xl font-black text-white italic tracking-tighter uppercase font-heading">
                PROGRAM <span class="underline decoration-neon underline-offset-8">MEMBERSHIP</span>
            </h3>
            <p class="mt-6 text-gray-400 max-w-2xl mx-auto font-medium">
                Dapatkan keuntungan eksklusif, diskon khusus, dan prioritas booking dengan menjadi member resmi kami.
            </p>
        </div>

        @php
            $remainingWeekday = null;
            $remainingWeekend = null;
            if ($activeMembership) {
                $tier = $activeMembership->tier;
                
                // Weekday limit
                if ($tier->discount_weekday_limit !== null) {
                    $weekdayBookings = \App\Models\Booking::where('user_id', auth()->id())
                        ->where('discount_amount', '>', 0)
                        ->whereNotIn('status', ['cancelled', 'expired'])
                        ->where('created_at', '>=', \Carbon\Carbon::parse($activeMembership->start_date)->startOfDay())
                        ->get()
                        ->filter(function($b) {
                            return !\Carbon\Carbon::parse($b->booking_date)->isWeekend();
                        })->count();
                    $remainingWeekday = max(0, $tier->discount_weekday_limit - $weekdayBookings);
                }

                // Weekend limit
                if ($tier->discount_weekend_limit !== null) {
                    $weekendBookings = \App\Models\Booking::where('user_id', auth()->id())
                        ->where('discount_amount', '>', 0)
                        ->whereNotIn('status', ['cancelled', 'expired'])
                        ->where('created_at', '>=', \Carbon\Carbon::parse($activeMembership->start_date)->startOfDay())
                        ->get()
                        ->filter(function($b) {
                            return \Carbon\Carbon::parse($b->booking_date)->isWeekend();
                        })->count();
                    $remainingWeekend = max(0, $tier->discount_weekend_limit - $weekendBookings);
                }
            }
        @endphp

        <!-- Status Card -->
        <div class="mb-16 bg-dark-card rounded-3xl border border-white/5 p-6 md:p-8 shadow-2xl relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-64 h-64 bg-neon/5 rounded-full blur-3xl -mr-32 -mt-32 transition-all duration-700 group-hover:bg-neon/10"></div>
            
            <div class="relative flex flex-col md:flex-row items-center justify-between gap-8">
                <div>
                    <h4 class="text-white/50 font-black uppercase tracking-widest text-xs mb-2">Status Keanggotaan</h4>
                    
                    <!-- Verifying Status -->
                    <div x-show="isChecking && !isActive" class="flex flex-col gap-2" x-cloak>
                        <div class="flex items-center gap-4">
                            <span class="text-4xl font-black text-white italic uppercase tracking-tighter">Memverifikasi...</span>
                            <span class="bg-yellow-500/10 text-yellow-500 border border-yellow-500/20 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest animate-pulse">PENDING</span>
                        </div>
                        <p class="text-gray-400 text-xs italic">Kami sedang memproses pembayaran Anda. Mohon tunggu sebentar...</p>
                    </div>

                    <!-- Dynamic Active Status -->
                    <div x-show="isActive" class="flex items-center gap-4" x-cloak>
                        <span class="text-4xl font-black text-white italic uppercase tracking-tighter" x-text="tierName"></span>
                        <span class="bg-neon/10 text-neon border border-neon/20 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest">AKTIF</span>
                    </div>
                    <p x-show="isActive" class="mt-4 text-gray-400" x-cloak>
                        Berlaku hingga: <span class="text-white font-bold" x-text="endDate"></span>
                    </p>
                    @if($activeMembership)
                    <div class="mt-4 flex flex-col sm:flex-row gap-4 sm:gap-8 text-xs font-bold uppercase tracking-wider text-gray-400">
                        <div>
                            Sisa Diskon Weekday: 
                            <span class="text-neon">
                                {{ $activeMembership->tier->discount_weekday_limit !== null ? $remainingWeekday . '/' . $activeMembership->tier->discount_weekday_limit . 'x' : 'Unlimited' }}
                            </span>
                        </div>
                        <div>
                            Sisa Diskon Weekend: 
                            <span class="text-neon">
                                {{ $activeMembership->tier->discount_weekend_limit !== null ? $remainingWeekend . '/' . $activeMembership->tier->discount_weekend_limit . 'x' : 'Unlimited' }}
                            </span>
                        </div>
                    </div>
                    @endif

                    <!-- Dynamic Inactive Status -->
                    <div x-show="!isActive && !isChecking" class="flex items-center gap-4">
                        <span class="text-4xl font-black text-white italic uppercase tracking-tighter">Bukan Member</span>
                        <span class="bg-red-500/10 text-red-500 border border-red-500/20 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest">TIDAK AKTIF</span>
                    </div>
                    <p x-show="!isActive && !isChecking" class="mt-4 text-gray-400">Upgrade sekarang untuk menikmati berbagai keuntungan.</p>
                </div>
                
                <div x-show="isActive" class="bg-white/5 border border-white/10 p-6 rounded-3xl text-center min-w-[200px]" x-cloak>
                    <span class="block text-neon text-3xl font-black italic" x-text="discount + '%'"></span>
                    <span class="text-white/50 text-[10px] font-black uppercase tracking-widest">Diskon berlaku</span>
                </div>
            </div>
        </div>

        <!-- Pricing Tiers -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($tiers as $tier)
            <div class="bg-dark-card rounded-3xl border border-white/5 p-6 shadow-2xl transition duration-500 hover:border-neon/30 hover:-translate-y-2 flex flex-col">
                <div class="mb-8">
                    <h4 class="text-neon font-black uppercase tracking-widest text-xs mb-2">{{ $tier->name }}</h4>
                    @if($activeMembership && $tier->price > $activeMembership->tier->price)
                        <div class="flex items-baseline gap-1">
                            <span class="text-4xl font-black text-white italic tracking-tighter">Rp {{ number_format($tier->price - $activeMembership->tier->price, 0, ',', '.') }}</span>
                            <span class="text-gray-500 text-xs font-bold">/ sisa</span>
                        </div>
                        <div class="mt-2 text-[10px] text-neon font-black uppercase tracking-wider flex items-center gap-1.5">
                            <i class="fas fa-tags"></i>
                            <span>Upgrade: Hemat Rp {{ number_format($activeMembership->tier->price, 0, ',', '.') }}</span>
                        </div>
                    @else
                        <div class="flex items-baseline gap-1">
                            <span class="text-4xl font-black text-white italic tracking-tighter">Rp {{ number_format($tier->price, 0, ',', '.') }}</span>
                            <span class="text-gray-500 text-xs font-bold">/ bulan</span>
                        </div>
                    @endif
                </div>

                <div class="flex-grow">
                    <ul class="space-y-4 mb-8">
                        @php
                            $benefits = explode("\n", $tier->description);
                        @endphp
                        @foreach($benefits as $benefit)
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-neon mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            <span class="text-gray-300 text-sm font-medium">{{ trim($benefit) }}</span>
                        </li>
                        @endforeach
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-neon mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            <span class="text-gray-300 text-sm font-medium">Diskon Weekday: {{ $tier->discount_weekday }}% ({{ $tier->discount_weekday_limit ? 'Limit: '.$tier->discount_weekday_limit.'x' : 'Unlimited' }})</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-neon mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            <span class="text-gray-300 text-sm font-medium">Diskon Weekend: {{ $tier->discount_weekend }}% ({{ $tier->discount_weekend_limit ? 'Limit: '.$tier->discount_weekend_limit.'x' : 'Unlimited' }})</span>
                        </li>
                    </ul>
                </div>

                <div class="mt-auto">
                    @if(!$activeMembership)
                        <!-- No active membership -> Daftar Sekarang -->
                        <button @click="subscribe('{{ $tier->id }}')" 
                                :disabled="isChecking"
                                :class="isChecking ? 'opacity-50 cursor-not-allowed' : ''"
                                class="w-full bg-neon text-dark py-4 rounded-2xl font-black uppercase tracking-tighter hover:bg-white transition shadow-lg text-sm flex items-center justify-center gap-2">
                            <span x-show="!isChecking">Daftar Sekarang</span>
                            <span x-show="isChecking" class="flex items-center gap-2" x-cloak>
                                <svg class="animate-spin h-4 w-4 text-dark" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                Memproses...
                            </span>
                        </button>
                    @elseif($activeMembership->membership_tier_id == $tier->id)
                        <!-- Active tier -> Paket Saat Ini -->
                        <button class="w-full bg-white/5 text-white/30 py-4 rounded-2xl font-black uppercase tracking-tighter border border-white/10 cursor-not-allowed text-sm" disabled>
                            Paket Saat Ini
                        </button>
                    @elseif($tier->price > $activeMembership->tier->price)
                        <!-- Higher tier -> Upgrade Sekarang -->
                        <button @click="subscribe('{{ $tier->id }}')" 
                                :disabled="isChecking"
                                :class="isChecking ? 'opacity-50 cursor-not-allowed' : ''"
                                class="w-full bg-white text-dark py-4 rounded-2xl font-black uppercase tracking-tighter hover:bg-neon transition shadow-lg text-sm flex items-center justify-center gap-2">
                            <span x-show="!isChecking">Upgrade Sekarang</span>
                            <span x-show="isChecking" class="flex items-center gap-2" x-cloak>
                                <svg class="animate-spin h-4 w-4 text-dark" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                Memproses...
                            </span>
                        </button>
                    @else
                        <!-- Cheaper/same tier -> Tidak Dapat Downgrade -->
                        <button class="w-full bg-white/5 text-white/30 py-4 rounded-2xl font-black uppercase tracking-tighter border border-white/10 cursor-not-allowed text-sm" disabled>
                            Tidak Dapat Downgrade
                        </button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@push('scripts')
    <script src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script>
        function membershipApp() {
            return {
                isActive: {{ $activeMembership ? 'true' : 'false' }},
                tierName: '{{ $activeMembership ? $activeMembership->tier->name : "" }}',
                endDate: '{{ $activeMembership ? $activeMembership->end_date->format("d M Y") : "" }}',
                discount: {{ $activeMembership ? (($activeMembership->tier->discount_weekday ?? 0) + ($activeMembership->tier->discount_weekend ?? 0)) : 0 }},
                receiptUrl: '{{ $activeMembership ? route("memberships.receipt", $activeMembership) : "#" }}',
                currentTierId: '{{ $activeMembership ? $activeMembership->membership_tier_id : "" }}',
                isChecking: false,
                isPolling: {{ $hasPendingMembership ? 'true' : 'false' }},

                subscribe(tierId) {
                    if (this.isChecking) return;
                    this.isChecking = true;

                    fetch(`/membership/subscribe/${tierId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            // If response is not 2xx, try to parse JSON error or throw response status
                            return response.json().then(err => {
                                throw new Error(err.message || 'HTTP Error ' + response.status);
                            }).catch(() => {
                                throw new Error('HTTP Error ' + response.status);
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success && data.snap_token) {
                            this.isPolling = true;
                            this.checkStatus();
                            window.snap.pay(data.snap_token, {
                                onSuccess: (result) => {
                                    this.checkStatus();
                                    this.isChecking = false;
                                },
                                onPending: (result) => {
                                    window.location.href = "{{ route('dashboard') }}?status=pending";
                                    this.isChecking = false;
                                },
                                onError: (result) => {
                                    alert("Pembayaran gagal!");
                                    this.isPolling = false;
                                    this.isChecking = false;
                                },
                                onClose: () => {
                                    console.log('User closed the popup');
                                    this.isChecking = false;
                                }
                            });
                        } else {
                            alert(data.message || 'Gagal mendapatkan token pembayaran. Silakan hubungi admin.');
                            this.isChecking = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Gagal memproses langganan: ' + error.message);
                        this.isChecking = false;
                    });
                },

                checkStatus() {
                    if (!this.isPolling) return;
                    
                    if (window.statusInterval) {
                        clearInterval(window.statusInterval);
                    }
                    
                    let attempts = 0;
                    const maxAttempts = 20; // 40 seconds total
                    
                    window.statusInterval = setInterval(() => {
                        fetch('{{ route("membership.check-status") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'active') {
                                this.isActive = true;
                                this.tierName = data.tier_name;
                                this.endDate = data.end_date;
                                this.discount = data.discount;
                                this.receiptUrl = data.receipt_url;
                                this.currentTierId = data.current_tier_id;
                                this.isPolling = false;
                                clearInterval(window.statusInterval);
                                
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Membership Aktif!',
                                    text: 'Selamat! Keanggotaan ' + data.tier_name + ' Anda sudah aktif.',
                                    background: '#1e293b',
                                    color: '#fff',
                                    confirmButtonColor: '#bef264'
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else if (data.status !== 'pending') {
                                clearInterval(window.statusInterval);
                                this.isPolling = false;
                            }
                            
                            attempts++;
                            if (attempts >= maxAttempts) {
                                clearInterval(window.statusInterval);
                                this.isPolling = false;
                                console.log('Checking stopped after max attempts');
                            }
                        })
                        .catch(err => {
                            console.error('Fetch error:', err);
                            clearInterval(window.statusInterval);
                            this.isPolling = false;
                        });
                    }, 2000);
                }
            }
        }
    </script>
@endpush
@endsection
