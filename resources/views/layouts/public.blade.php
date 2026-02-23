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
                        <a href="/#about" class="text-[11px] font-black uppercase tracking-[0.2em] hover:text-neon transition">Fasilitas</a>
                        <a href="/#courts" class="text-[11px] font-black uppercase tracking-[0.2em] hover:text-neon transition">Harga</a>
                        
                        @auth
                            <a href="{{ route('dashboard') }}" class="text-[11px] font-black uppercase tracking-[0.2em] hover:text-neon transition">Booking</a>
                            <div class="flex items-center space-x-6 ml-4 pl-6 border-l border-white/10">
                                <a href="{{ route('profile.edit') }}" class="text-[11px] font-black uppercase tracking-[0.2em] hover:text-neon transition">Profile</a>
                                <form method="POST" action="{{ route('logout') }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-[11px] font-black uppercase tracking-[0.2em] text-red-500/80 hover:text-red-500 transition">Logout</button>
                                </form>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="text-[11px] font-black uppercase tracking-[0.2em] hover:text-neon transition">Booking</a>
                            <a href="{{ route('login') }}" class="bg-neon text-dark px-8 py-3 rounded-xl font-black uppercase tracking-tighter hover:scale-105 transition transform active:scale-95 shadow-[0_0_20px_rgba(190,242,100,0.3)] ml-4">Login</a>
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
                <a href="/#about" class="block text-[13px] font-black uppercase tracking-[0.2em] hover:text-neon transition">Fasilitas</a>
                <a href="/#courts" class="block text-[13px] font-black uppercase tracking-[0.2em] hover:text-neon transition">Harga</a>
                
                @auth
                    <a href="{{ route('dashboard') }}" class="block text-[13px] font-black uppercase tracking-[0.2em] hover:text-neon transition">Booking</a>
                    <div class="pt-6 border-t border-white/5 space-y-6">
                        <a href="{{ route('profile.edit') }}" class="block text-[13px] font-black uppercase tracking-[0.2em] hover:text-neon transition">Profile Settings</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block text-[13px] font-black uppercase tracking-[0.2em] text-red-500 font-black">LOGOUT</button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="block text-[13px] font-black uppercase tracking-[0.2em] hover:text-neon transition">Booking</a>
                    <a href="{{ route('login') }}" class="block bg-neon text-dark text-center py-4 rounded-2xl font-black uppercase tracking-tighter">Login Sekarang</a>
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
                        The elite standard in padel court management.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="bg-white/5 p-3 rounded-xl text-white hover:text-neon hover:bg-white/10 transition border border-white/5"><i class="fab fa-facebook-f text-lg"></i></a>
                        <a href="#" class="bg-white/5 p-3 rounded-xl text-white hover:text-neon hover:bg-white/10 transition border border-white/5"><i class="fab fa-instagram text-lg"></i></a>
                        <a href="#" class="bg-white/5 p-3 rounded-xl text-white hover:text-neon hover:bg-white/10 transition border border-white/5"><i class="fab fa-twitter text-lg"></i></a>
                    </div>
                </div>
                <div>
                    <h5 class="text-white font-black uppercase tracking-widest text-[10px] mb-6 italic">HQ Contact</h5>
                    <ul class="space-y-4 text-gray-500 font-bold uppercase text-[10px] tracking-widest">
                        <li class="flex items-start">
                            <span class="text-neon mr-3 mt-0.5"><i class="fas fa-map-marker-alt"></i></span>
                            {{ $landingContent->contact_address ?? 'Jakarta Selatan' }}
                        </li>
                        <li class="flex items-center">
                            <span class="text-neon mr-3"><i class="fas fa-phone-alt"></i></span>
                            {{ $landingContent->contact_phone ?? '+62 812 3456 7890' }}
                        </li>
                    </ul>
                </div>
                <div>
                    <h5 class="text-white font-black uppercase tracking-widest text-[10px] mb-6 italic">Access</h5>
                    <ul class="space-y-4 text-gray-500 font-bold uppercase text-[10px] tracking-widest">
                        <li><a href="/#about" class="hover:text-neon transition">Our Arena</a></li>
                        <li><a href="/#courts" class="hover:text-neon transition">Court Specs</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-neon transition">Join Community</a></li>
                    </ul>
                </div>
            </div>
            <div class="max-w-7xl mx-auto border-t border-white/5 mt-8 pt-8 text-center text-[9px] font-black uppercase tracking-[0.5em] text-white/20">
                <p>&copy; 2026 PadelHub Indonesia.</p>
            </div>
        </footer>

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
        @stack('scripts')
    </body>
</html>
