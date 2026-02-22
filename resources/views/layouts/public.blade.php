<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'PadelMate') }} - @yield('title', 'Sporty Dark Modern Booking')</title>
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
        <nav class="bg-dark/80 backdrop-blur-xl border-b border-white/5 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20">
                    <div class="flex items-center">
                        <a href="/" class="text-2xl font-black text-white tracking-tighter flex items-center group">
                            <span class="bg-neon text-dark p-1.5 rounded-lg mr-2 group-hover:rotate-12 transition-transform duration-300">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12z"/></svg>
                            </span>
                            PADEL<span class="text-neon">MATE</span>
                        </a>
                    </div>
                    
                    <div class="hidden md:flex items-center space-x-10">
                        <a href="{{ route('welcome') }}" class="text-sm font-bold uppercase tracking-widest hover:text-neon transition">Home</a>
                        @auth
                            <a href="/#courts" class="text-sm font-bold uppercase tracking-widest hover:text-neon transition">Book Court</a>
                            <a href="{{ route('dashboard') }}" class="text-sm font-bold uppercase tracking-widest hover:text-neon transition">My Bookings</a>
                            <a href="{{ route('profile.edit') }}" class="text-sm font-bold uppercase tracking-widest hover:text-neon transition">Profile</a>
                        @else
                            <a href="/#about" class="text-sm font-bold uppercase tracking-widest hover:text-neon transition">Fasilitas</a>
                            <a href="/#courts" class="text-sm font-bold uppercase tracking-widest hover:text-neon transition">Harga</a>
                        @endauth
                    </div>

                    <div class="flex items-center space-x-6">
                        @auth
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="bg-white/5 hover:bg-white/10 text-white px-5 py-2 rounded-xl font-bold transition border border-white/10 uppercase text-xs tracking-widest">Logout</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-bold uppercase tracking-widest hover:text-neon transition">Login</a>
                            <a href="{{ route('register') }}" class="bg-neon text-dark px-7 py-3 rounded-xl font-black uppercase tracking-tighter hover:scale-105 transition transform active:scale-95 shadow-[0_0_20px_rgba(190,242,100,0.3)]">Daftar</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <main>
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-dark-card border-t border-white/5 py-32 px-4 relative">
            <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-20 relative z-10">
                <div class="col-span-1 md:col-span-2">
                    <a href="/" class="text-4xl font-black text-white tracking-tighter mb-8 block font-heading">PADEL<span class="text-neon">MATE</span></a>
                    <p class="mb-12 max-w-sm leading-relaxed text-gray-500 font-medium text-lg italic">
                        The elite standard in padel court management. Powered by athletes, for athletes.
                    </p>
                    <div class="flex space-x-6">
                        <a href="#" class="bg-white/5 p-4 rounded-2xl text-white hover:text-neon hover:bg-white/10 transition border border-white/5"><i class="fab fa-facebook-f text-xl"></i></a>
                        <a href="#" class="bg-white/5 p-4 rounded-2xl text-white hover:text-neon hover:bg-white/10 transition border border-white/5"><i class="fab fa-instagram text-xl"></i></a>
                        <a href="#" class="bg-white/5 p-4 rounded-2xl text-white hover:text-neon hover:bg-white/10 transition border border-white/5"><i class="fab fa-twitter text-xl"></i></a>
                    </div>
                </div>
                <div>
                    <h5 class="text-white font-black uppercase tracking-widest text-xs mb-8 italic">Contact HQ</h5>
                    <ul class="space-y-6 text-gray-500 font-bold uppercase text-xs tracking-widest">
                        <li class="flex items-start">
                            <span class="text-neon mr-3 mt-0.5"><i class="fas fa-map-marker-alt"></i></span>
                            {{ $landingContent->contact_address ?? 'Kebayoran Elite Arena, Jakarta Selatan' }}
                        </li>
                        <li class="flex items-center">
                            <span class="text-neon mr-3"><i class="fas fa-phone-alt"></i></span>
                            {{ $landingContent->contact_phone ?? '+62 812 3456 7890' }}
                        </li>
                    </ul>
                </div>
                <div>
                    <h5 class="text-white font-black uppercase tracking-widest text-xs mb-8 italic">Fast Access</h5>
                    <ul class="space-y-6 text-gray-500 font-bold uppercase text-xs tracking-widest">
                        <li><a href="/#about" class="hover:text-neon transition">Our Arena</a></li>
                        <li><a href="/#courts" class="hover:text-neon transition">Court Specs</a></li>
                        <li><a href="/#availability" class="hover:text-neon transition">Live Schedule</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-neon transition">Join Community</a></li>
                    </ul>
                </div>
            </div>
            <div class="max-w-7xl mx-auto border-t border-white/5 mt-32 pt-12 text-center text-[10px] font-black uppercase tracking-[0.5em] text-white/20">
                <p>&copy; 2026 PadelMate Indonesia. Built for the modern athlete.</p>
            </div>
        </footer>

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
        @stack('scripts')
    </body>
</html>
