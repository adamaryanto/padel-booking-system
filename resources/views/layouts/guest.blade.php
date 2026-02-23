<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'PadelHub') }} - Access Portal</title>

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
                        neon: '#bef264',
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
        .carbon-bg {
            background-image: url('https://www.transparenttextures.com/patterns/carbon-fibre.png');
        }
        .glow-neon {
            box-shadow: 0 0 20px rgba(190, 242, 100, 0.2);
        }
    </style>
</head>
<body class="antialiased bg-dark text-gray-300 font-sans carbon-bg min-h-screen flex items-center justify-center p-4 relative overflow-x-hidden py-12">
    
    <!-- Decorative Glows -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-neon/10 rounded-full blur-[100px] -mr-48 -mt-48 transition-all duration-1000"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-neon/5 rounded-full blur-[100px] -ml-48 -mb-48 transition-all duration-1000"></div>

    <div class="max-w-md w-full relative z-10">
        <!-- Logo Area -->
        <div class="text-center mb-10">
            <a href="/" class="text-4xl font-black text-white tracking-tighter hover:scale-105 transition-transform duration-300 inline-block">
                PADEL<span class="text-neon">HUB</span>
            </a>
            <p class="text-gray-500 font-bold uppercase tracking-[0.4em] text-[9px] mt-2">Elite Athlete Portal</p>
        </div>

        <!-- Auth Card -->
        <div class="bg-dark-card/50 backdrop-blur-xl border border-white/5 rounded-[2.5rem] shadow-2xl p-8 sm:p-10 glow-neon transition-all duration-500">
            {{ $slot }}
        </div>

        <div class="text-center mt-12 text-[10px] font-black uppercase tracking-[0.5em] text-white/20">
            &copy; {{ date('Y') }} PadelHub Indonesia. Driven by Excellence.
        </div>
    </div>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</body>
</html>
