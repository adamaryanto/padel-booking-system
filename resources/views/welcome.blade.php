@extends('layouts.public')

@push('styles')
<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .animate-fade-in-up {
        opacity: 0;
        animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>
@endpush

@section('content')
<div x-data="availabilityCheck()">
    
    <!-- 1. HERO SECTION -->
    @if(($landingExtras['hero_status'] ?? 'active') !== 'hidden')
    @php
        $heroBg = !empty($landingExtras['hero_bg_image']) ? url('storage/'.$landingExtras['hero_bg_image']) : ($landingContent->hero_image ? (str_starts_with($landingContent->hero_image, 'http') ? $landingContent->hero_image : url('storage/'.$landingContent->hero_image)) : asset('images/hero.png'));
    @endphp
    <header class="relative bg-cover pt-36 pb-40 md:pt-48 md:pb-52 px-4 sm:px-6 lg:px-8 overflow-hidden" 
            style="background-image: url('{{ $heroBg }}'); background-position: center 70%;">
        
        <!-- Dark Scrim Overlay for Readability -->
        <div class="absolute inset-0 bg-dark/80 z-0"></div>
        
        <!-- Bottom Smooth Fade Gradient to Next Section -->
        <div class="absolute bottom-0 left-0 right-0 h-64 bg-gradient-to-t from-dark to-transparent z-0"></div>
        
        <div class="max-w-5xl mx-auto relative z-10 text-center">
            <!-- Hero Content Centered -->
            <div class="flex flex-col items-center justify-center">
                <!-- Top Tagline Badge -->
                <span class="text-neon font-black uppercase tracking-[0.3em] text-xs sm:text-sm mb-4 block animate-fade-in-up" style="animation-delay: 50ms;">
                    PLATFORM BOOKING PADEL NO. 1 DI INDONESIA
                </span>

                <h1 class="text-4xl sm:text-5xl md:text-6xl font-black text-white mb-6 leading-tight tracking-tight font-heading uppercase text-center animate-fade-in-up">
                    {!! nl2br(e($landingContent->hero_title ?? 'Main Padel Jadi Lebih Mudah')) !!}
                </h1>
                <p class="text-base md:text-lg text-gray-300 mb-8 max-w-2xl mx-auto leading-relaxed font-semibold text-center animate-fade-in-up" style="animation-delay: 150ms;">
                    {{ $landingContent->hero_subtitle ?? 'Cari, booking, dan bayar lapangan padel favoritmu dalam hitungan menit.' }}
                </p>
                
                <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-4 w-full sm:w-auto mx-auto animate-fade-in-up" style="animation-delay: 300ms;">
                    <a href="{{ $landingContent->hero_cta_link ?? '#courts' }}" class="bg-neon text-dark px-8 py-4 rounded-xl font-black text-lg uppercase tracking-tighter hover:bg-neon/90 hover:scale-105 active:scale-95 transition-all duration-300 shadow-[0_0_30px_rgba(190,242,100,0.4)] text-center w-full sm:w-auto">
                        {{ $landingContent->hero_cta_text ?? 'Booking Sekarang' }}
                    </a>
                    <a href="{{ $landingExtras['hero_secondary_link'] ?? '#courts' }}" class="bg-white/10 backdrop-blur-md border border-white/20 text-white px-8 py-4 rounded-xl font-black text-lg uppercase tracking-tighter hover:bg-white/20 hover:scale-105 active:scale-95 transition-all duration-300 text-center w-full sm:w-auto">
                        {{ $landingExtras['hero_secondary_text'] ?? 'Lihat Lapangan' }}
                    </a>
                </div>

                <!-- Bottom trust badge/features note -->
                <p class="text-white/40 text-[10px] font-black uppercase tracking-widest mt-8 animate-fade-in-up" style="animation-delay: 400ms;">
                    <i class="fas fa-bolt text-neon mr-1"></i> Konfirmasi Instan & Pembayaran Aman Terintegrasi
                </p>
            </div>

            <!-- Optional illustration image centered below buttons (if set) -->
            @if(!empty($landingExtras['hero_bg_image']) && $landingContent->hero_image)
            <div class="mt-20 max-w-md mx-auto animate-fade-in-up" style="animation-delay: 450ms;">
                <div class="p-3 bg-white/5 backdrop-blur-md border border-white/10 rounded-[2rem] overflow-hidden shadow-2xl transform hover:scale-105 transition-all duration-500">
                    <img src="{{ str_starts_with($landingContent->hero_image, 'http') ? $landingContent->hero_image : url('storage/' . $landingContent->hero_image) }}" alt="Hero Illustration" class="w-full h-auto rounded-[1.5rem] object-cover max-h-[300px]">
                </div>
            </div>
            @endif
        </div>
    </header>
    @endif

    <!-- 2. STATISTIK SECTION -->
    <section class="pt-20 pb-20 bg-dark relative border-b border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="text-neon font-black uppercase tracking-[0.25em] text-[10px] mb-2 block">Dipercaya Ribuan Pemain Padel</span>
                <h3 class="text-3xl font-black text-white italic tracking-tighter uppercase font-heading">STATISTIK UTAMA</h3>
            </div>
            @php
                $statCourtsTarget = (int) preg_replace('/[^0-9]/', '', $landingExtras['stat_courts'] ?? '500');
                $statCitiesTarget = (int) preg_replace('/[^0-9]/', '', $landingExtras['stat_cities'] ?? '20');
                $statMembersTarget = (int) preg_replace('/[^0-9]/', '', $landingExtras['stat_members'] ?? '10000');
                $statBookingsTarget = (int) preg_replace('/[^0-9]/', '', $landingExtras['stat_bookings'] ?? '50000');
            @endphp
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div class="bg-dark-card/30 p-6 rounded-2xl border border-white/5">
                    <h4 class="text-neon font-black text-4xl sm:text-5xl mb-2" x-data="{ count: 0, target: {{ $statCourtsTarget }}, init() { let interval = setInterval(() => { if(this.count < this.target) { this.count += Math.ceil((this.target - this.count) / 10); } else { this.count = this.target; clearInterval(interval); } }, 30); } }" x-text="count.toLocaleString('id-ID') + '+'">0+</h4>
                    <p class="text-white/60 text-xs font-black uppercase tracking-wider">Lapangan Terdaftar</p>
                </div>
                <div class="bg-dark-card/30 p-6 rounded-2xl border border-white/5">
                    <h4 class="text-neon font-black text-4xl sm:text-5xl mb-2" x-data="{ count: 0, target: {{ $statCitiesTarget }}, init() { let interval = setInterval(() => { if(this.count < this.target) { this.count += Math.ceil((this.target - this.count) / 6); } else { this.count = this.target; clearInterval(interval); } }, 30); } }" x-text="count.toLocaleString('id-ID') + '+'">0+</h4>
                    <p class="text-white/60 text-xs font-black uppercase tracking-wider">Kota di Indonesia</p>
                </div>
                <div class="bg-dark-card/30 p-6 rounded-2xl border border-white/5">
                    <h4 class="text-neon font-black text-4xl sm:text-5xl mb-2" x-data="{ count: 0, target: {{ $statMembersTarget }}, init() { let interval = setInterval(() => { if(this.count < this.target) { this.count += Math.ceil((this.target - this.count) / 12); } else { this.count = this.target; clearInterval(interval); } }, 30); } }" x-text="count.toLocaleString('id-ID') + '+'">0+</h4>
                    <p class="text-white/60 text-xs font-black uppercase tracking-wider">Pengguna Aktif</p>
                </div>
                <div class="bg-dark-card/30 p-6 rounded-2xl border border-white/5">
                    <h4 class="text-neon font-black text-4xl sm:text-5xl mb-2" x-data="{ count: 0, target: {{ $statBookingsTarget }}, init() { let interval = setInterval(() => { if(this.count < this.target) { this.count += Math.ceil((this.target - this.count) / 12); } else { this.count = this.target; clearInterval(interval); } }, 30); } }" x-text="count.toLocaleString('id-ID') + '+'">0+</h4>
                    <p class="text-white/60 text-xs font-black uppercase tracking-wider">Booking Berhasil</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. POPULAR COURTS SECTION -->
    <section id="courts" class="py-20 md:py-32 bg-dark relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-end mb-20 gap-8">
                <div class="max-w-xl text-center md:text-left">
                    <h2 class="text-neon font-black uppercase tracking-[.3em] text-[10px] mb-4">Paling Sering Dipesan</h2>
                    <h3 class="text-5xl font-black text-white italic tracking-tighter uppercase font-heading">LAPANGAN <span class="underline decoration-neon underline-offset-8">POPULER</span></h3>
                </div>
                <p class="text-gray-500 font-bold max-w-xs uppercase text-right leading-tight tracking-widest text-[10px] border-r-4 border-neon pr-4">
                    PILIH DARI LAPANGAN STANDAR PROFESIONAL KAMI
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
                @php
                    $displayCourts = [];
                    if (!empty($landingExtras['popular_courts'])) {
                        $displayCourts = array_filter($landingExtras['popular_courts'], function($c) {
                            return ($c['status'] ?? 'active') === 'active';
                        });
                    }
                @endphp
                
                @forelse($displayCourts as $courtItem)
                    <!-- CMS-configured Popular Court Card -->
                    <div class="bg-dark-card rounded-3xl overflow-hidden border border-white/5 hover:border-neon transition-all duration-500 group shadow-2xl hover:-translate-y-4">
                        <div class="aspect-[4/5] relative overflow-hidden bg-gray-900">
                            @if(!empty($courtItem['image']))
                                <img src="{{ url('storage/' . $courtItem['image']) }}" alt="{{ $courtItem['name'] }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700 brightness-75 group-hover:brightness-100">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-dark bg-neon opacity-20 italic font-black text-4xl">PADEL</div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-dark via-transparent to-transparent opacity-85"></div>
                            
                            <!-- Rating Badge -->
                            <div class="absolute top-6 right-6 bg-dark-card/80 border border-white/10 px-3 py-1.5 rounded-xl text-white font-black text-xs flex items-center gap-1.5 backdrop-blur-md">
                                <i class="fas fa-star text-neon"></i>
                                <span>4.9/5</span>
                            </div>

                            <div class="absolute bottom-8 left-8 right-8">
                                <div class="bg-neon text-dark inline-block px-4 py-1.5 rounded-lg text-xs font-black uppercase mb-4 shadow-lg">
                                    Rp {{ number_format($courtItem['price'] ?? 150000, 0, ',', '.') }} / Jam
                                </div>
                                <h4 class="text-2xl font-black text-white italic uppercase tracking-tighter mb-2">{{ $courtItem['name'] }}</h4>
                                <p class="text-white/60 text-xs font-bold uppercase tracking-wider flex items-center gap-1.5">
                                    <i class="fas fa-map-marker-alt text-neon"></i> Lokasi: {{ $courtItem['location'] }}
                                </p>
                            </div>
                        </div>
                        <div class="p-6">
                            <p class="text-gray-400 text-sm mb-8 line-clamp-3 italic font-medium">
                                {{ $courtItem['desc'] }}
                            </p>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <button class="bg-white/5 border border-white/10 text-white py-4 rounded-xl font-black uppercase tracking-wider hover:bg-white/10 transition-all text-xs flex items-center justify-center gap-2">
                                    <i class="far fa-heart"></i> Favorit
                                </button>
                                <a href="#availability" class="bg-neon text-dark py-4 rounded-xl font-black uppercase tracking-wider hover:bg-white transition-all text-xs text-center" @click="courtId = ''; checked = false;">
                                    Booking
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <!-- Fallback to database courts -->
                    @forelse($courts as $court)
                        @php
                            $loc = 'Jakarta';
                            $rating = '4.9';
                            if (str_contains(strtolower($court->name), 'panoramic')) {
                                $loc = 'Tangerang';
                            } elseif (str_contains(strtolower($court->name), 'indoor')) {
                                $loc = 'Jakarta Barat';
                            } elseif (str_contains(strtolower($court->name), 'outdoor')) {
                                $loc = 'Jakarta Selatan';
                            }
                        @endphp
                        <div class="bg-dark-card rounded-3xl overflow-hidden border border-white/5 hover:border-neon transition-all duration-500 group shadow-2xl hover:-translate-y-4">
                            <div class="aspect-[4/5] relative overflow-hidden bg-gray-900">
                                @if($court->photo)
                                    <img src="{{ url('storage/' . $court->photo) }}" alt="{{ $court->name }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700 brightness-75 group-hover:brightness-100">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-dark bg-neon opacity-20 italic font-black text-4xl">PADEL</div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-dark via-transparent to-transparent opacity-85"></div>
                                
                                <div class="absolute top-6 right-6 bg-dark-card/80 border border-white/10 px-3 py-1.5 rounded-xl text-white font-black text-xs flex items-center gap-1.5 backdrop-blur-md">
                                    <i class="fas fa-star text-neon"></i>
                                    <span>{{ $rating }}/5</span>
                                </div>

                                <div class="absolute bottom-8 left-8 right-8">
                                    <div class="bg-neon text-dark inline-block px-4 py-1.5 rounded-lg text-xs font-black uppercase mb-4 shadow-lg">
                                        Rp {{ number_format($court->price_weekday ?: $court->price_per_hour, 0, ',', '.') }} / Jam
                                    </div>
                                    <h4 class="text-2xl font-black text-white italic uppercase tracking-tighter mb-2">{{ $court->name }}</h4>
                                    <p class="text-white/60 text-xs font-bold uppercase tracking-wider flex items-center gap-1.5">
                                        <i class="fas fa-map-marker-alt text-neon"></i> Lokasi: {{ $loc }}
                                    </p>
                                </div>
                            </div>
                            <div class="p-6">
                                <p class="text-gray-400 text-sm mb-8 line-clamp-2 italic font-medium">
                                    {{ $court->description }}
                                </p>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <button class="bg-white/5 border border-white/10 text-white py-4 rounded-xl font-black uppercase tracking-wider hover:bg-white/10 transition-all text-xs flex items-center justify-center gap-2">
                                        <i class="far fa-heart"></i> Favorit
                                    </button>
                                    <a href="{{ route('customer.courts.show', $court) }}" class="bg-neon text-dark py-4 rounded-xl font-black uppercase tracking-wider hover:bg-white transition-all text-xs text-center">
                                        Booking
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-20 text-center border-2 border-dashed border-white/5 rounded-[3rem]">
                            <h3 class="text-2xl font-black text-white/20 italic tracking-tighter uppercase">Tidak ada arena yang aktif</h3>
                        </div>
                    @endforelse
                @endforelse
            </div>
        </div>
    </section>

    <!-- 4. FEATURES SECTION -->
    <section class="py-20 md:py-32 bg-dark-card/20 border-y border-white/5 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20">
                <span class="text-neon font-black uppercase tracking-[0.25em] text-[10px] mb-2 block">The PadelHub Experience</span>
                <h3 class="text-5xl font-black text-white italic tracking-tighter uppercase font-heading">KENAPA MEMILIH <span class="text-neon">PADELHUB</span></h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @php
                    $feats = [];
                    if (!empty($landingExtras['features'])) {
                        $feats = array_filter($landingExtras['features'], function($f) {
                            return ($f['status'] ?? 'active') === 'active';
                        });
                    }
                @endphp
                
                @forelse($feats as $feat)
                    <div class="bg-dark-card p-8 rounded-3xl border border-white/5 hover:border-neon transition-all duration-500 group">
                        <div class="text-neon mb-6 transform group-hover:scale-110 transition-transform">
                            <i class="{{ $feat['icon'] ?? 'fas fa-star' }} fa-3x"></i>
                        </div>
                        <h4 class="text-white font-black text-xl uppercase italic mb-4">{{ $feat['title'] }}</h4>
                        <p class="text-gray-500 text-sm font-medium leading-relaxed">{{ $feat['desc'] }}</p>
                    </div>
                @empty
                    <!-- Fallback hardcoded features -->
                    <div class="bg-dark-card p-8 rounded-3xl border border-white/5 hover:border-neon transition-all duration-500 group">
                        <div class="text-neon mb-6 transform group-hover:scale-110 transition-transform"><i class="fas fa-calendar-check fa-3x"></i></div>
                        <h4 class="text-white font-black text-xl uppercase italic mb-4">Booking Instan</h4>
                        <p class="text-gray-500 text-sm font-medium leading-relaxed">Reservasi lapangan hanya dalam beberapa klik.</p>
                    </div>
                    <div class="bg-dark-card p-8 rounded-3xl border border-white/5 hover:border-neon transition-all duration-500 group">
                        <div class="text-neon mb-6 transform group-hover:scale-110 transition-transform"><i class="fas fa-clock fa-3x"></i></div>
                        <h4 class="text-white font-black text-xl uppercase italic mb-4">Jadwal Real-Time</h4>
                        <p class="text-gray-500 text-sm font-medium leading-relaxed">Lihat ketersediaan lapangan secara langsung.</p>
                    </div>
                    <div class="bg-dark-card p-8 rounded-3xl border border-white/5 hover:border-neon transition-all duration-500 group">
                        <div class="text-neon mb-6 transform group-hover:scale-110 transition-transform"><i class="fas fa-shield-alt fa-3x"></i></div>
                        <h4 class="text-white font-black text-xl uppercase italic mb-4">Pembayaran Aman</h4>
                        <p class="text-gray-500 text-sm font-medium leading-relaxed">Mendukung berbagai metode pembayaran digital.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- 5. TESTIMONIALS SECTION -->
    <section class="py-20 md:py-32 bg-dark">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20">
                <span class="text-neon font-black uppercase tracking-[0.25em] text-[10px] mb-2 block">Kepuasan Pemain</span>
                <h3 class="text-5xl font-black text-white italic tracking-tighter uppercase font-heading">TESTIMONI <span class="text-neon">PENGGUNA</span></h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @php
                    $testis = [];
                    if (!empty($landingExtras['testimonials'])) {
                        $testis = array_filter($landingExtras['testimonials'], function($t) {
                            return ($t['status'] ?? 'active') === 'active';
                        });
                    }
                @endphp

                @forelse($testis as $testi)
                    <div class="bg-dark-card p-8 rounded-3xl border border-white/5 flex flex-col justify-between hover:border-neon/30 transition duration-300">
                        <div>
                            <div class="text-neon mb-6">
                                @for($i = 0; $i < ($testi['rating'] ?? 5); $i++)
                                    <i class="fas fa-star"></i>
                                @endfor
                                @for($i = ($testi['rating'] ?? 5); $i < 5; $i++)
                                    <i class="far fa-star opacity-40"></i>
                                @endfor
                            </div>
                            <p class="text-gray-300 text-sm font-medium italic leading-relaxed">
                                "{{ $testi['review'] }}"
                            </p>
                        </div>
                        <div class="mt-8 pt-6 border-t border-white/5 flex items-center gap-3">
                            @if(!empty($testi['photo']))
                                <img src="{{ url('storage/' . $testi['photo']) }}" alt="{{ $testi['name'] }}" class="w-10 h-10 rounded-full object-cover border border-white/10 flex-shrink-0">
                            @else
                                <div class="w-10 h-10 rounded-full bg-neon/10 text-neon flex items-center justify-center font-bold text-xs uppercase flex-shrink-0">
                                    {{ substr($testi['name'] ?? 'U', 0, 1) }}
                                </div>
                            @endif
                            <div>
                                <h5 class="text-white font-black text-sm uppercase mb-0">— {{ $testi['name'] }}</h5>
                            </div>
                        </div>
                    </div>
                @empty
                    <!-- Fallback testimonials (3 cards) -->
                    <div class="bg-dark-card p-8 rounded-3xl border border-white/5 flex flex-col justify-between hover:border-neon/30 transition duration-300">
                        <div>
                            <div class="text-neon mb-6"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                            <p class="text-gray-300 text-sm font-medium italic leading-relaxed">"Lapangan padel terbaik di Jakarta! Karpet pro tur-nya sangat ramah lutut, lampu malam tidak silau, dan booking via web super praktis."</p>
                        </div>
                        <div class="mt-8 pt-6 border-t border-white/5 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-neon/10 text-neon flex items-center justify-center font-bold text-xs uppercase flex-shrink-0">R</div>
                            <h5 class="text-white font-black text-sm uppercase mb-0">— Reza Rahadian</h5>
                        </div>
                    </div>
                    <div class="bg-dark-card p-8 rounded-3xl border border-white/5 flex flex-col justify-between hover:border-neon/30 transition duration-300">
                        <div>
                            <div class="text-neon mb-6"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                            <p class="text-gray-300 text-sm font-medium italic leading-relaxed">"Suka sekali dengan atmosfer komunitas di PadelHub. Kafenya estetik, showernya bersih, dan stafnya sangat membantu untuk pemula."</p>
                        </div>
                        <div class="mt-8 pt-6 border-t border-white/5 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-neon/10 text-neon flex items-center justify-center font-bold text-xs uppercase flex-shrink-0">C</div>
                            <h5 class="text-white font-black text-sm uppercase mb-0">— Chelsea Islan</h5>
                        </div>
                    </div>
                    <div class="bg-dark-card p-8 rounded-3xl border border-white/5 flex flex-col justify-between hover:border-neon/30 transition duration-300">
                        <div>
                            <div class="text-neon mb-6"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                            <p class="text-gray-300 text-sm font-medium italic leading-relaxed">"Sebagai mantan atlet, saya sangat merekomendasikan PadelHub. Standar lapangannya presisi, pantulan bola sempurna, dan slot jadwal selalu teratur."</p>
                        </div>
                        <div class="mt-8 pt-6 border-t border-white/5 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-neon/10 text-neon flex items-center justify-center font-bold text-xs uppercase flex-shrink-0">B</div>
                            <h5 class="text-white font-black text-sm uppercase mb-0">— Bambang Pamungkas</h5>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- 6. MEMBERSHIP SECTION -->
    <section id="membership" class="py-20 md:py-32 bg-dark-card/20 border-t border-white/5 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20">
                <span class="text-neon font-black uppercase tracking-[0.25em] text-[10px] mb-2 block">Daftar Paket Keanggotaan</span>
                <h3 class="text-5xl font-black text-white italic tracking-tighter uppercase font-heading">MEMBERSHIP <span class="text-neon">PLANS</span></h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-3xl mx-auto">
                @php
                    $memberships = [];
                    if (!empty($landingExtras['membership'])) {
                        $memberships = array_filter($landingExtras['membership'], function($m) {
                            return ($m['status'] ?? 'active') === 'active';
                        });
                    }
                @endphp

                @forelse($memberships as $index => $mem)
                    @php
                        $isRecommended = $index === 1 || stripos($mem['name'], 'premium') !== false || stripos($mem['name'], 'rekomendasi') !== false;
                        $memFeatures = array_filter(explode("\n", $mem['features'] ?? ''));
                    @endphp
                    <div class="bg-dark p-8 rounded-3xl flex flex-col justify-between hover:border-white transition-all duration-300 relative {{ $isRecommended ? 'border border-neon shadow-[0_0_30px_rgba(190,242,100,0.15)]' : 'border border-white/5' }}">
                        @if($isRecommended)
                            <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-neon text-dark text-[8px] font-black uppercase tracking-[0.2em] px-4 py-1.5 rounded-full border border-neon/20 shadow-md">
                                RECOMMENDED
                            </div>
                        @endif
                        <div>
                            <h4 class="text-white font-black text-xl uppercase italic mb-2">{{ $mem['name'] }}</h4>
                            <div class="text-neon font-black text-3xl mb-6">
                                @if(($mem['price'] ?? 0) == 0)
                                    Gratis
                                @else
                                    Rp {{ number_format($mem['price'], 0, ',', '.') }}<span class="text-xs text-white/40 font-medium lowercase">/{{ $mem['duration'] ?? 'Bulan' }}</span>
                                @endif
                            </div>
                            <ul class="text-gray-400 text-xs font-bold uppercase tracking-wider space-y-4 mb-10">
                                @foreach($memFeatures as $featItem)
                                    <li class="flex items-center gap-3">
                                        <i class="fas fa-check text-neon flex-shrink-0"></i>
                                        <span>{{ ltrim($featItem, '- ') }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        
                        @if(($mem['price'] ?? 0) == 0)
                            @guest
                                <a href="{{ route('register') }}" class="block w-full text-center bg-white/5 border border-white/10 hover:bg-white/10 text-white py-4 rounded-xl font-black text-xs uppercase tracking-widest transition">
                                    Mulai Gratis
                                </a>
                            @else
                                <button class="block w-full text-center bg-white/5 border border-white/10 text-white/50 py-4 rounded-xl font-black text-xs uppercase tracking-widest cursor-not-allowed" disabled>
                                    Mulai Gratis
                                </button>
                            @endguest
                        @else
                            <a href="{{ route('membership.index') }}" class="block w-full text-center bg-neon text-dark py-4 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-white transition shadow-md">
                                Berlangganan
                            </a>
                        @endif
                    </div>
                @empty
                    <!-- Fallback membership plans (2 cards) -->
                    <div class="bg-dark p-8 rounded-3xl border border-white/5 flex flex-col justify-between hover:border-white transition duration-300">
                        <div>
                            <h4 class="text-white font-black text-xl uppercase italic mb-2">Basic Plan</h4>
                            <div class="text-neon font-black text-3xl mb-6">Gratis</div>
                            <ul class="text-gray-400 text-xs font-bold uppercase tracking-wider space-y-4 mb-10">
                                <li class="flex items-center gap-3"><i class="fas fa-check text-neon"></i> Cari & Cek Lapangan</li>
                                <li class="flex items-center gap-3"><i class="fas fa-check text-neon"></i> Booking Online 24/7</li>
                                <li class="flex items-center gap-3"><i class="fas fa-check text-neon"></i> Riwayat Transaksi Lengkap</li>
                            </ul>
                        </div>
                        <a href="{{ route('register') }}" class="block w-full text-center bg-white/5 border border-white/10 hover:bg-white/10 text-white py-4 rounded-xl font-black text-xs uppercase tracking-widest transition">Mulai Gratis</a>
                    </div>
                    
                    <div class="bg-dark p-8 rounded-3xl flex flex-col justify-between hover:border-white transition-all duration-300 relative border border-neon shadow-[0_0_30px_rgba(190,242,100,0.15)]">
                        <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-neon text-dark text-[8px] font-black uppercase tracking-[0.2em] px-4 py-1.5 rounded-full border border-neon/20 shadow-md">
                            RECOMMENDED
                        </div>
                        <div>
                            <h4 class="text-white font-black text-xl uppercase italic mb-2">Premium Plan</h4>
                            <div class="text-neon font-black text-3xl mb-6">
                                Rp 99.000<span class="text-xs text-white/40 font-medium lowercase">/Bulan</span>
                            </div>
                            <ul class="text-gray-400 text-xs font-bold uppercase tracking-wider space-y-4 mb-10">
                                <li class="flex items-center gap-3"><i class="fas fa-check text-neon"></i> Slot Reservasi H-14</li>
                                <li class="flex items-center gap-3"><i class="fas fa-check text-neon"></i> Diskon Pemesanan 10%</li>
                                <li class="flex items-center gap-3"><i class="fas fa-check text-neon"></i> Akses Event & Coaching</li>
                                <li class="flex items-center gap-3"><i class="fas fa-check text-neon"></i> Gratis Raket & Bola</li>
                            </ul>
                        </div>
                        <a href="{{ route('membership.index') }}" class="block w-full text-center bg-neon text-dark py-4 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-white transition shadow-md">Berlangganan</a>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Availability / Cek Jadwal Section -->
    <section id="availability" class="py-20 md:py-32 bg-dark relative">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 bg-dark-card p-8 md:p-16 rounded-[2.5rem] md:rounded-[4rem] border border-white/5 shadow-[0_0_100px_rgba(0,0,0,0.5)] relative overflow-hidden">
            <div class="absolute top-0 right-0 p-8">
                <div class="w-16 h-16 border-t-4 border-r-4 border-neon opacity-20"></div>
            </div>
            
            <div class="text-center mb-16">
                <h2 class="text-white font-black italic text-5xl uppercase tracking-tighter mb-4 font-heading">CEK <span class="text-neon">JADWAL</span></h2>
                <p class="text-gray-500 font-bold uppercase tracking-widest text-xs">Pantau ketersediaan secara real-time</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mb-12">
                <div class="space-y-3">
                    <label class="block text-xs font-black text-white/40 uppercase tracking-widest pl-2">Pilih Arena</label>
                    <select x-model="courtId" class="w-full bg-dark border-transparent rounded-2xl p-5 focus:ring-2 focus:ring-neon text-white font-bold uppercase tracking-tighter">
                        <option value="">-- Pilih Arena --</option>
                        @foreach($courts as $court)
                            <option value="{{ $court->id }}">{{ $court->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-3">
                    <label class="block text-xs font-black text-white/40 uppercase tracking-widest pl-2">Pilih Tanggal</label>
                    <input type="date" :value="date" @input="date = $event.target.value" min="{{ date('Y-m-d') }}" class="w-full bg-dark border-transparent rounded-2xl p-5 focus:ring-2 focus:ring-neon text-white font-bold uppercase tracking-tighter">
                </div>
            </div>

            <!-- Availability Result -->
            <div x-show="checked" x-cloak x-transition.opacity class="mt-16 pt-16 border-t border-white/5">
                <h4 class="font-black text-white italic text-3xl uppercase tracking-tighter mb-10 text-center">SLOT UNTUK TANGGAL <span class="text-neon" x-text="date"></span></h4>
                <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-6">
                    <template x-for="slot in slots" :key="slot.time">
                        <div class="p-5 rounded-[2rem] text-center font-black transition relative overflow-hidden group border border-transparent" 
                             :class="slot.available ? 'bg-neon/5 text-neon border-neon/20 hover:bg-neon/10' : 'bg-red-500/5 text-red-500 border-red-500/20 opacity-40'">
                            <span class="text-lg italic" x-text="slot.time"></span>
                            <div class="text-[8px] uppercase tracking-[0.2em] font-black mt-2" x-text="slot.available ? 'Tersedia' : 'Terisi'"></div>
                            <div x-show="slot.available" class="absolute -top-1 -right-1 w-2 h-2 bg-neon rounded-full animate-ping"></div>
                        </div>
                    </template>
                </div>
                <div class="mt-16 text-center">
                    @guest
                        <a href="{{ route('login') }}" class="inline-block bg-white text-dark px-12 py-4 rounded-2xl font-black uppercase tracking-tighter text-sm hover:bg-neon transition shadow-xl">Daftar untuk Memesan</a>
                    @else
                        <a :href="'/courts/' + courtId" class="inline-block bg-white text-dark px-12 py-4 rounded-2xl font-black uppercase tracking-tighter text-sm hover:bg-neon transition shadow-xl">Pesan Sekarang</a>
                    @endguest
                </div>
            </div>
        </div>
    </section>



    <!-- 8. FAQ SECTION -->
    <section id="faq" class="py-20 md:py-32 bg-dark border-t border-white/5">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20 text-balance">
                <h2 class="text-neon font-black uppercase tracking-[.3em] text-[10px] mb-4">Ada Pertanyaan?</h2>
                <h3 class="text-5xl font-black text-white italic tracking-tighter uppercase font-heading">PERTANYAAN YANG SERING <span class="text-neon">DIAJUKAN</span></h3>
            </div>

            <div class="space-y-4" x-data="{ active: null }">
                @foreach($faqs as $index => $faq)
                    <div class="bg-dark-card rounded-2xl border border-white/5 overflow-hidden transition-all duration-300" :class="active === {{ $index }} ? 'border-neon/30 bg-dark-card/80' : 'hover:border-white/10'">
                        <button @click="active = active === {{ $index }} ? null : {{ $index }}" class="w-full p-6 text-left flex justify-between items-center group">
                            <span class="text-white font-black uppercase italic tracking-tighter text-lg transition-colors" :class="active === {{ $index }} ? 'text-neon' : ''">
                                {{ $faq->question }}
                            </span>
                            <svg class="w-6 h-6 text-neon transition-transform duration-500" :class="active === {{ $index }} ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="active === {{ $index }}" x-collapse x-cloak>
                            <div class="px-8 pb-8 text-gray-400 font-medium leading-relaxed text-sm">
                                {!! nl2br(e($faq->answer)) !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- 9. FOOTER SECTION -->
    <footer class="bg-dark-card/30 border-t border-white/5 py-16 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto grid grid-cols-2 md:grid-cols-5 gap-12 mb-12">
            <!-- Brand Column -->
            <div class="col-span-2 md:col-span-1 space-y-4">
                <a href="/" class="text-3xl font-black text-white tracking-tighter inline-block">
                    @if(!empty($landingExtras['setting_logo']))
                        <img src="{{ url('storage/' . $landingExtras['setting_logo']) }}" alt="PadelHub Logo" style="max-height: 45px; object-fit: contain;">
                    @else
                        PADEL<span class="text-neon">HUB</span>
                    @endif
                </a>
                <p class="text-gray-500 text-xs italic font-semibold leading-relaxed">
                    Standar elit dalam manajemen lapangan padel.
                </p>
            </div>
            
            <!-- Navigation Column -->
            <div>
                <h5 class="text-white font-black text-xs uppercase tracking-widest mb-6">Navigasi</h5>
                <ul class="space-y-4 text-xs font-bold uppercase tracking-wider text-white/55">
                    <li><a href="#" class="hover:text-neon transition">Beranda</a></li>
                    <li><a href="#courts" class="hover:text-neon transition">Lapangan</a></li>
                    <li><a href="#membership" class="hover:text-neon transition">Membership</a></li>
                    <li><a href="#faq" class="hover:text-neon transition">FAQ</a></li>
                </ul>
            </div>

            <!-- Contacts Column -->
            <div>
                <h5 class="text-white font-black text-xs uppercase tracking-widest mb-6">Kontak</h5>
                <ul class="space-y-4 text-xs font-bold uppercase tracking-wider text-white/55">
                    <li class="flex items-start">
                        <span class="text-neon mr-2 mt-0.5"><i class="fas fa-map-marker-alt"></i></span>
                        <span>{{ $landingContent->contact_address ?? 'Jakarta Selatan' }}</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-neon mr-2"><i class="fas fa-phone-alt"></i></span>
                        <span>{{ $landingContent->contact_phone ?? '+62 812 3456 7890' }}</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-neon mr-2"><i class="fas fa-envelope"></i></span>
                        <a href="mailto:{{ $landingContent->contact_email ?? 'hello@padelhub.com' }}" class="lowercase hover:text-neon">{{ $landingContent->contact_email ?? 'hello@padelhub.com' }}</a>
                    </li>
                </ul>
            </div>

            <!-- Services Column -->
            <div>
                <h5 class="text-white font-black text-xs uppercase tracking-widest mb-6">Layanan</h5>
                <ul class="space-y-4 text-xs font-bold uppercase tracking-wider text-white/55">
                    <li><a href="#availability" class="hover:text-neon transition">Cek Jadwal</a></li>
                    <li><a href="{{ route('register') }}" class="hover:text-neon transition">Registrasi</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-neon transition">Masuk Akun</a></li>
                </ul>
            </div>

            <!-- Social Media Column -->
            <div>
                <h5 class="text-white font-black text-xs uppercase tracking-widest mb-6">Ikuti Kami</h5>
                <ul class="space-y-4 text-xs font-bold uppercase tracking-wider text-white/55">
                    @if(!empty($landingExtras['social_instagram']))
                        <li><a href="{{ $landingExtras['social_instagram'] }}" target="_blank" class="hover:text-neon transition flex items-center gap-2"><i class="fab fa-instagram text-sm"></i> Instagram</a></li>
                    @endif
                    @if(!empty($landingExtras['social_facebook']))
                        <li><a href="{{ $landingExtras['social_facebook'] }}" target="_blank" class="hover:text-neon transition flex items-center gap-2"><i class="fab fa-facebook text-sm"></i> Facebook</a></li>
                    @endif
                    @if(!empty($landingExtras['social_tiktok']))
                        <li><a href="{{ $landingExtras['social_tiktok'] }}" target="_blank" class="hover:text-neon transition flex items-center gap-2"><i class="fab fa-tiktok text-sm"></i> TikTok</a></li>
                    @endif
                    @if(!empty($landingExtras['social_youtube']))
                        <li><a href="{{ $landingExtras['social_youtube'] }}" target="_blank" class="hover:text-neon transition flex items-center gap-2"><i class="fab fa-youtube text-sm"></i> YouTube</a></li>
                    @endif
                    @if(!empty($landingContent->whatsapp_number))
                        <li class="mt-2">
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $landingContent->whatsapp_number) }}" target="_blank" class="bg-green-500/10 text-green-500 border border-green-500/20 px-3 py-1.5 rounded-lg inline-flex items-center hover:bg-green-500 hover:text-white transition">
                                <i class="fab fa-whatsapp mr-1.5 text-sm"></i> Chat Admin
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        <div class="max-w-7xl mx-auto pt-8 border-t border-white/5 flex flex-col sm:flex-row justify-between items-center gap-6">
            <span class="text-gray-600 text-xs font-bold uppercase tracking-wider">
                {!! $landingExtras['footer_copyright'] ?? '&copy; 2026 PadelHub Indonesia. All Rights Reserved.' !!}
            </span>
        </div>
    </footer>

</div>

@push('scripts')
<script>
    function availabilityCheck() {
        return {
            courtId: '',
            date: '{{ date('Y-m-d') }}',
            isLoading: false,
            checked: false,
            slots: [],
            init() {
                this.$watch('courtId', () => this.checkAvailability());
                this.$watch('date', () => this.checkAvailability());
            },
            checkAvailability() {
                if (!this.courtId) {
                    this.checked = false;
                    return;
                }
                this.isLoading = true;
                fetch(`/api/availability?court_id=${this.courtId}&date=${this.date}`)
                    .then(res => res.json())
                    .then(data => {
                        this.slots = data;
                        this.checked = true;
                        this.isLoading = false;
                    })
                    .catch(err => {
                        console.error(err);
                        this.isLoading = false;
                    });
            }
        }
    }
</script>
@endpush
@endsection
