<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'PadelHub') }} - @yield('title', 'Sporty Dark Modern Booking')</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&family=Poppins:wght@600;800&display=swap" rel="stylesheet">
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.10.3/cdn.min.js" defer></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            dark: '#0f172a',
                            'dark-card': '#1e293b',
                            neon: '#bef264', // Lime-300
                        },
                        fontFamily: {
                            sans: ['Inter', 'sans-serif'],
                            heading: ['Poppins', 'sans-serif'],
                        }
                    }
                }
            }
        </script>
        <style>
            [x-cloak] { display: none !important; }
            ::-webkit-scrollbar { width: 8px; }
            ::-webkit-scrollbar-track { background: #0f172a; }
            ::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 10px; }
            ::-webkit-scrollbar-thumb:hover { background: #bef264; }
        </style>
        @stack('styles')
    </head>
    <body class="antialiased bg-dark text-gray-300 font-sans selection:bg-neon selection:text-dark text-[15px]">
        <!-- Navbar -->
        <nav class="bg-dark/80 backdrop-blur-xl border-b border-white/5 sticky top-0 z-50" x-data="{ mobileMenuOpen: false }">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20">
                    <!-- Logo Area -->
                    <div class="flex items-center">
                        <a href="/" class="text-2xl font-black text-white tracking-tighter hover:scale-105 transition-transform duration-300">
                            PADEL<span class="text-neon">HUB</span>
                        </a>
                    </div>
                    
                    <!-- Desktop Navigation -->
                    <div class="hidden md:flex items-center space-x-10">
                        <a href="{{ route('welcome') }}" class="text-[11px] font-black uppercase tracking-[0.2em] hover:text-neon transition">Home</a>
                        
                        @auth
                            @if(auth()->user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="text-[11px] font-black uppercase tracking-[0.2em] hover:text-neon transition">Dashboard</a>
                                <a href="{{ route('admin.courts.index') }}" class="text-[11px] font-black uppercase tracking-[0.2em] hover:text-neon transition">Kelola Lapangan</a>
                                <a href="{{ route('admin.bookings.index') }}" class="text-[11px] font-black uppercase tracking-[0.2em] hover:text-neon transition">Booking & Pembayaran</a>
                                <a href="{{ route('admin.membership-tiers.index') }}" class="text-[11px] font-black uppercase tracking-[0.2em] hover:text-neon transition">Membership</a>
                                <a href="{{ route('profile.edit') }}" class="text-[11px] font-black uppercase tracking-[0.2em] hover:text-neon transition">Profil</a>
                            @else
                                <a href="{{ route('welcome') }}#courts" class="text-[11px] font-black uppercase tracking-[0.2em] hover:text-neon transition">Pesan Arena</a>
                                <a href="{{ route('dashboard') }}" class="text-[11px] font-black uppercase tracking-[0.2em] hover:text-neon transition">Aktivitas Saya</a>
                                <a href="{{ route('membership.index') }}" class="text-[11px] font-black uppercase tracking-[0.2em] hover:text-neon transition">Membership</a>
                            @endif
                            <!-- Profile Dropdown -->
                            <div class="relative ml-4 pl-6 border-l border-white/10" x-data="{ open: false }">
                                <button @click="open = !open" @click.away="open = false" class="flex items-center space-x-2 text-[11px] font-black uppercase tracking-[0.2em] hover:text-neon transition focus:outline-none">
                                    <span>Profil</span>
                                    <svg class="w-3 h-3 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                                </button>
                                <div x-show="open" 
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     class="absolute right-0 mt-4 w-48 bg-dark-card border border-white/10 rounded-2xl shadow-2xl py-2 z-50" x-cloak>
                                    <a href="{{ route('profile.edit') }}" class="block px-6 py-3 text-[10px] font-black uppercase tracking-widest text-white hover:text-neon hover:bg-white/5 transition">Pengaturan</a>
                                    <div class="border-t border-white/5 my-1"></div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-6 py-3 text-[10px] font-black uppercase tracking-widest text-red-500/80 hover:text-red-500 hover:bg-white/5 transition">Logout</button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="/#about" class="text-[11px] font-black uppercase tracking-[0.2em] hover:text-neon transition">Fasilitas</a>
                            <a href="/#courts" class="text-[11px] font-black uppercase tracking-[0.2em] hover:text-neon transition">Harga</a>
                            <a href="{{ route('login') }}" class="text-[11px] font-black uppercase tracking-[0.2em] hover:text-neon transition">Masuk</a>
                            <a href="{{ route('register') }}" class="bg-neon text-dark px-8 py-3 rounded-xl font-black uppercase tracking-tighter hover:scale-105 transition transform active:scale-95 shadow-[0_0_20px_rgba(190,242,100,0.3)] ml-4">Daftar</a>
                        @endauth
                    </div>

                    <!-- Mobile Menu Button -->
                    <div class="md:hidden flex items-center">
                        <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-white p-2 focus:outline-none">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!mobileMenuOpen">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                            </svg>
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="mobileMenuOpen" x-cloak>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Navigation Menu -->
            <div x-show="mobileMenuOpen" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-4"
                 class="md:hidden bg-dark-card border-b border-white/5 px-6 py-8 space-y-6" x-cloak>
                <a href="{{ route('welcome') }}" class="block text-[13px] font-black uppercase tracking-[0.2em] hover:text-neon transition">Home</a>
                
                @auth
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="block text-[13px] font-black uppercase tracking-[0.2em] hover:text-neon transition">Dashboard</a>
                        <a href="{{ route('admin.courts.index') }}" class="block text-[13px] font-black uppercase tracking-[0.2em] hover:text-neon transition">Kelola Lapangan</a>
                        <a href="{{ route('admin.bookings.index') }}" class="block text-[13px] font-black uppercase tracking-[0.2em] hover:text-neon transition">Kelola Booking & Pembayaran</a>
                        <a href="{{ route('admin.membership-tiers.index') }}" class="block text-[13px] font-black uppercase tracking-[0.2em] hover:text-neon transition">Kelola Membership</a>
                        <a href="{{ route('profile.edit') }}" class="block text-[13px] font-black uppercase tracking-[0.2em] hover:text-neon transition">Profil</a>
                    @else
                        <a href="{{ route('welcome') }}#courts" class="block text-[13px] font-black uppercase tracking-[0.2em] hover:text-neon transition">Pesan Arena</a>
                        <a href="{{ route('dashboard') }}" class="block text-[13px] font-black uppercase tracking-[0.2em] hover:text-neon transition">Aktivitas Saya</a>
                        <a href="{{ route('membership.index') }}" class="block text-[13px] font-black uppercase tracking-[0.2em] hover:text-neon transition">Membership</a>
                    @endif
                    <div class="pt-6 border-t border-white/5 space-y-6">
                        <p class="text-[10px] font-black text-white/30 uppercase tracking-[0.2em]">Profile</p>
                        <a href="{{ route('profile.edit') }}" class="block text-[13px] font-black uppercase tracking-[0.2em] hover:text-neon transition">Settings</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block text-[13px] font-black uppercase tracking-[0.2em] text-red-500 font-black">KELUAR</button>
                        </form>
                    </div>
                @else
                    <a href="/#about" class="block text-[13px] font-black uppercase tracking-[0.2em] hover:text-neon transition">Fasilitas</a>
                    <a href="/#courts" class="block text-[13px] font-black uppercase tracking-[0.2em] hover:text-neon transition">Harga</a>
                    <a href="{{ route('login') }}" class="block text-[13px] font-black uppercase tracking-[0.2em] hover:text-neon transition">Masuk</a>
                    <a href="{{ route('register') }}" class="block bg-neon text-dark text-center py-4 rounded-2xl font-black uppercase tracking-tighter">Daftar Sekarang</a>
                @endauth
            </div>
        </nav>

        <main>
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-dark-card border-t border-white/5 py-10 px-4 relative">
            <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-12 relative z-10">
                <div class="col-span-1 md:col-span-2">
                    <a href="/" class="text-3xl font-black text-white tracking-tighter mb-4 block font-heading">PADEL<span class="text-neon">HUB</span></a>
                    <p class="mb-6 max-w-sm leading-relaxed text-gray-500 font-medium text-sm italic">
                        Standar elit dalam manajemen lapangan padel.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="bg-white/5 p-3 rounded-xl text-white hover:text-neon hover:bg-white/10 transition border border-white/5"><i class="fab fa-facebook-f text-lg"></i></a>
                        <a href="#" class="bg-white/5 p-3 rounded-xl text-white hover:text-neon hover:bg-white/10 transition border border-white/5"><i class="fab fa-instagram text-lg"></i></a>
                        <a href="#" class="bg-white/5 p-3 rounded-xl text-white hover:text-neon hover:bg-white/10 transition border border-white/5"><i class="fab fa-twitter text-lg"></i></a>
                    </div>
                </div>
                <div>
                    <h5 class="text-white font-black uppercase tracking-widest text-[10px] mb-6 italic">Hubungi Kami</h5>
                    <ul class="space-y-4 text-gray-500 font-bold uppercase text-[10px] tracking-widest">
                        <li class="flex items-start">
                            <span class="text-neon mr-3 mt-0.5"><i class="fas fa-map-marker-alt"></i></span>
                            {{ $landingContent->contact_address ?? 'Jakarta Selatan' }}
                        </li>
                        <li class="flex items-center">
                            <span class="text-neon mr-3"><i class="fas fa-phone-alt"></i></span>
                            {{ $landingContent->contact_phone ?? '+62 812 3456 7890' }}
                        </li>
                        <li class="flex items-center">
                            <span class="text-neon mr-3"><i class="fas fa-envelope"></i></span>
                            {{ $landingContent->contact_email ?? 'hello@padelhub.com' }}
                        </li>
                        @if($landingContent->whatsapp_number)
                        <li class="mt-4">
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $landingContent->whatsapp_number) }}" target="_blank" class="bg-green-500/10 text-green-500 border border-green-500/20 px-4 py-2 rounded-xl inline-flex items-center hover:bg-green-500 hover:text-white transition group">
                                <i class="fab fa-whatsapp mr-2 text-lg"></i> Chat Admin
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
                <div>
                    <h5 class="text-white font-black uppercase tracking-widest text-[10px] mb-6 italic">Akses</h5>
                    <ul class="space-y-4 text-gray-500 font-bold uppercase text-[10px] tracking-widest">
                        <li><a href="/#about" class="hover:text-neon transition">Arena Kami</a></li>
                        <li><a href="/#courts" class="hover:text-neon transition">Spek Lapangan</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-neon transition">Gabung Komunitas</a></li>
                    </ul>
                </div>
            </div>
            <div class="max-w-7xl mx-auto border-t border-white/5 mt-8 pt-8 text-center text-[9px] font-black uppercase tracking-[0.5em] text-white/20">
                <p>&copy; 2026 PadelHub Indonesia.</p>
            </div>
        </footer>

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            window.addEventListener('load', function() {
                setTimeout(function() {
                    @if(session('success'))
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: "{{ session('success') }}",
                            showConfirmButton: false,
                            timer: 2000,
                            background: '#1e293b',
                            color: '#fff',
                            customClass: {
                                popup: 'rounded-[2rem] border border-white/5 shadow-2xl backdrop-blur-xl'
                            }
                        });
                    @endif

                    @if(session('error'))
                        Swal.fire({
                            icon: 'error',
                            title: 'Oppss!',
                            text: "{{ session('error') }}",
                            confirmButtonColor: '#ef4444',
                            background: '#1e293b',
                            color: '#fff',
                            customClass: {
                                popup: 'rounded-[2rem] border border-white/5 shadow-2xl backdrop-blur-xl'
                            }
                        });
                    @endif
                }, 500);
            });
        </script>

        @stack('scripts')
    </body>
</html>
