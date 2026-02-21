<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - PadelMate</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&family=Poppins:wght@600;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.tailwindcss.com"></script>
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
    </head>
    <body class="font-sans text-gray-300 antialiased bg-dark selection:bg-neon selection:text-dark">
        <!-- navbar -->
        <nav class="bg-dark/80 backdrop-blur-xl border-b border-white/5 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="/" class="text-xl font-black text-white tracking-tighter flex items-center">
                            <span class="bg-neon text-dark p-1.5 rounded-lg mr-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12z"/></svg>
                            </span>
                            PADEL<span class="text-neon">MATE</span>
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <div class="min-h-[calc(100vh-64px)] flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]">
            <div class="w-full sm:max-w-md mt-6 px-10 py-12 bg-dark-card shadow-2xl overflow-hidden sm:rounded-[3rem] border border-white/5 relative">
                <div class="absolute top-0 left-0 w-full h-1 bg-neon"></div>
                {{ $slot }}
            </div>
            
            <div class="mt-8 text-center text-[10px] font-black uppercase tracking-[0.5em] text-white/20">
                &copy; 2026 PadelMate Indonesia.
            </div>
        </div>
    </body>
</html>
