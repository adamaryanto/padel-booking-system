@extends('layouts.public')

@section('title', 'Membership')

@section('content')
<div class="py-24 px-4 sm:px-6 lg:px-8 bg-dark min-h-screen" x-data="membershipApp()" x-init="if(isChecking) checkStatus()">
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
                    <div class="flex items-baseline gap-1">
                        <span class="text-4xl font-black text-white italic tracking-tighter">Rp {{ number_format($tier->price, 0, ',', '.') }}</span>
                        <span class="text-gray-500 text-xs font-bold">/ bulan</span>
                    </div>
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
                            <span class="text-gray-300 text-sm font-medium">Diskon {{ $tier->discount_percentage }}% setiap booking</span>
                        </li>
                    </ul>
                </div>

                <div class="mt-auto">
                    <template x-if="!isActive">
                        <button @click="subscribe('{{ $tier->id }}')" 
                                :disabled="isChecking"
                                :class="isChecking ? 'opacity-50 cursor-not-allowed' : ''"
                                class="w-full bg-neon text-dark py-4 rounded-2xl font-black uppercase tracking-tighter hover:bg-white transition shadow-lg text-sm flex items-center justify-center gap-2">
                            <span x-show="!isChecking">Daftar Sekarang</span>
                            <span x-show="isChecking" class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-dark" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                Memproses...
                            </span>
                        </button>
                    </template>
                    <template x-if="isActive && currentTierId == '{{ $tier->id }}'">
                        <button class="w-full bg-white/5 text-white/30 py-4 rounded-2xl font-black uppercase tracking-tighter border border-white/10 cursor-not-allowed text-sm" disabled>
                            Paket Saat Ini
                        </button>
                    </template>
                    <template x-if="isActive && currentTierId != '{{ $tier->id }}'">
                        <button class="w-full bg-white/5 text-white/30 py-4 rounded-2xl font-black uppercase tracking-tighter border border-white/10 cursor-not-allowed text-sm" disabled>
                            Paket Terkunci
                        </button>
                    </template>
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
                discount: {{ $activeMembership ? $activeMembership->tier->discount_percentage : 0 }},
                receiptUrl: '{{ $activeMembership ? route("memberships.receipt", $activeMembership) : "#" }}',
                currentTierId: '{{ $activeMembership ? $activeMembership->membership_tier_id : "" }}',
                isChecking: {{ $hasPendingMembership ? 'true' : 'false' }},

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
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.snap.pay(data.snap_token, {
                                onSuccess: (result) => {
                                    this.checkStatus();
                                },
                                onPending: (result) => {
                                    this.checkStatus();
                                },
                                onError: (result) => {
                                    alert("Pembayaran gagal!");
                                    this.isChecking = false;
                                },
                                onClose: () => {
                                    console.log('User closed the popup');
                                    this.checkStatus();
                                }
                            });
                        } else {
                            alert(data.message || 'Terjadi kesalahan');
                            this.isChecking = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Gagal memproses langganan');
                        this.isChecking = false;
                    });
                },

                checkStatus() {
                    this.isChecking = true;
                    let attempts = 0;
                    const maxAttempts = 20; // 40 seconds total
                    
                    const interval = setInterval(() => {
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
                                this.isChecking = false;
                                clearInterval(interval);
                                
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Membership Aktif!',
                                    text: 'Selamat! Keanggotaan ' + data.tier_name + ' Anda sudah aktif.',
                                    background: '#1e293b',
                                    color: '#fff',
                                    confirmButtonColor: '#bef264'
                                });
                            }
                            
                            attempts++;
                            if (attempts >= maxAttempts) {
                                clearInterval(interval);
                                this.isChecking = false;
                                // If still not active after 40s, show a note but don't force reload
                                console.log('Checking stopped after max attempts');
                            }
                        })
                        .catch(err => {
                            console.error('Fetch error:', err);
                            clearInterval(interval);
                            this.isChecking = false;
                        });
                    }, 2000);
                }
            }
        }
    </script>
@endpush
@endsection
