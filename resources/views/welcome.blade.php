<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PadelMate - Sporty Dark Modern Booking</title>
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
    </head>
    <body class="antialiased bg-dark text-gray-300 font-sans selection:bg-neon selection:text-dark">
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
                        <a href="#about" class="text-sm font-bold uppercase tracking-widest hover:text-neon transition">About</a>
                        <a href="#courts" class="text-sm font-bold uppercase tracking-widest hover:text-neon transition">Courts</a>
                        <a href="#availability" class="text-sm font-bold uppercase tracking-widest hover:text-neon transition">Schedule</a>
                    </div>
                    <div class="flex items-center space-x-6">
                        @auth
                            <a href="{{ route('dashboard') }}" class="text-sm font-bold uppercase tracking-widest text-white hover:text-neon transition">Dashboard</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="bg-white/5 hover:bg-white/10 text-white px-5 py-2 rounded-xl font-bold transition border border-white/10">Logout</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-bold uppercase tracking-widest hover:text-neon transition">Login</a>
                            <a href="{{ route('register') }}" class="bg-neon text-dark px-7 py-3 rounded-xl font-black uppercase tracking-tighter hover:scale-105 transition transform active:scale-95 shadow-[0_0_20px_rgba(190,242,100,0.3)]">Daftar</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <header class="relative pt-24 pb-32 px-4 sm:px-6 lg:px-8 overflow-hidden bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]">
            <div class="max-w-7xl mx-auto relative z-10 flex flex-col md:flex-row items-center">
                <div class="md:w-3/5 text-center md:text-left">
                    <div class="inline-block bg-neon/10 text-neon px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-[0.2em] mb-6 border border-neon/20">
                        {{ $landingContent->hero_subtitle ?? 'Premium Padel Experience' }}
                    </div>
                    @php
                        $heroTitle = $landingContent->hero_title ?? 'UNLOCK YOUR PEAK PERFORMANCE';
                        $words = explode(' ', $heroTitle);
                        $lastWord = array_pop($words);
                        $firstWords = implode(' ', $words);
                    @endphp
                    <h1 class="text-6xl md:text-8xl font-black text-white mb-8 leading-[0.9] tracking-tighter font-heading">
                        {{ $firstWords }} <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-neon to-lime-500 uppercase">{{ $lastWord }}</span>
                    </h1>
                    <p class="text-xl text-gray-400 mb-12 max-w-xl leading-relaxed font-medium">
                        {{ $landingContent->about_description ?? 'Dominasi lapangan dengan fasilitas kelas dunia. Platform booking tercepat untuk atlit modern.' }}
                    </p>
                    <div class="flex flex-col sm:flex-row justify-center md:justify-start space-y-4 sm:space-y-0 sm:space-x-6">
                        <a href="{{ $landingContent->hero_cta_link ?? '#courts' }}" class="bg-neon text-dark px-10 py-5 rounded-2xl font-black text-xl uppercase tracking-tighter hover:bg-white transition shadow-[0_0_30px_rgba(190,242,100,0.4)]">
                            {{ $landingContent->hero_cta_text ?? 'Book Now' }}
                        </a>
                        <a href="#availability" class="bg-white/5 backdrop-blur-md border border-white/10 text-white px-10 py-5 rounded-2xl font-black text-xl uppercase tracking-tighter hover:bg-white/10 transition">
                            View Slots
                        </a>
                    </div>
                </div>
                <!-- Sporty Action Shot -->
                <div class="md:w-2/5 mt-20 md:mt-0 relative">
                    <div class="absolute -inset-4 bg-neon/20 rounded-[3rem] blur-3xl opacity-20 animate-pulse"></div>
                    <div class="relative rounded-[3rem] overflow-hidden border-2 border-white/10 shadow-2xl rotate-3 hover:rotate-0 transition-transform duration-500 group">
                        <img src="{{ $landingContent->hero_image ? (str_starts_with($landingContent->hero_image, 'http') ? $landingContent->hero_image : asset('storage/'.$landingContent->hero_image)) : asset('images/hero.jpg') }}" 
                             alt="Padel Action Shot" 
                             class="w-full h-full object-cover brightness-75 group-hover:brightness-100 transition duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-dark/80 via-transparent to-transparent"></div>
                    </div>
                    <!-- Trophy/Badge overlay -->
                    <div class="absolute -bottom-6 -left-6 bg-neon text-dark p-6 rounded-2xl shadow-2xl -rotate-6 hidden lg:block">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM5.884 6.68a1 1 0 10-1.415-1.414l.707-.707a1 1 0 101.415 1.414l-.707.707zm1.414 8.486a1 1 0 10-1.414-1.414l-.707.707a1 1 0 101.414 1.414l.707-.707zm11.314-1.414l-.707-.707a1 1 0 10-1.414 1.414l.707.707a1 1 0 101.414-1.414zm-1.414-8.486l.707-.707a1 1 0 10-1.414-1.414l-.707.707a1 1 0 101.414 1.414zM10 11a3 3 0 100-6 3 3 0 000 6z"/><path fill-rule="evenodd" d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12z" clip-rule="evenodd"/></svg>
                        <div class="font-black text-xs uppercase tracking-tighter mt-2">Elite<br>Standard</div>
                    </div>
                </div>
            </div>
            <!-- Glow background -->
            <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-neon/10 rounded-full blur-[120px] -mr-64 -mt-64"></div>
            <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-lime-500/5 rounded-full blur-[120px] -ml-64 -mb-64"></div>
        </header>

        <!-- About Us Section -->
        <section id="about" class="py-32 relative border-y border-white/5 bg-dark-card/30">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row items-center gap-20">
                    <div class="md:w-1/2 relative group">
                        <div class="absolute -inset-4 bg-neon/20 rounded-[3rem] blur-2xl opacity-0 group-hover:opacity-100 transition duration-500"></div>
                        <div class="bg-dark rounded-[3rem] overflow-hidden aspect-square border border-white/10 relative">
                             <img src="{{ $landingContent->about_image ? (str_starts_with($landingContent->about_image, 'http') ? $landingContent->about_image : asset('storage/'.$landingContent->about_image)) : asset('images/about.jpg') }}" 
                                  alt="Empty Padel Court" 
                                  class="w-full h-full object-cover opacity-60 group-hover:opacity-100 transition duration-500 group-hover:scale-105">
                             <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                <span class="text-neon font-black text-6xl opacity-20 italic underline decoration-neon tracking-tighter transition-opacity group-hover:opacity-0">ARENA</span>
                             </div>
                        </div>
                    </div>
                    <div class="md:w-1/2">
                        <div class="flex items-center space-x-4 mb-6">
                            <div class="h-1 w-12 bg-neon"></div>
                            <span class="text-neon font-black uppercase tracking-[.3em] text-xs">{{ $landingContent->about_subtitle ?? 'The Arena' }}</span>
                        </div>
                        <h2 class="text-5xl font-black text-white mb-8 leading-tight font-heading italic uppercase tracking-tighter">
                            {{ $landingContent->about_title ?? 'THE FUTURE OF PADEL IS HERE' }}
                        </h2>
                        <p class="text-gray-400 text-lg mb-12 leading-relaxed font-medium">
                            {{ $landingContent->about_description ?? 'PadelMate bukan sekadar lapangan. Ini adalah ekosistem bagi pemenang.' }}
                        </p>
                        
                        <div class="grid grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <h4 class="text-neon font-black text-3xl italic">01</h4>
                                <h5 class="text-white font-bold text-lg uppercase">WPT Standards</h5>
                                <p class="text-sm text-gray-500">Lapangan berstandar World Padel Tour.</p>
                            </div>
                            <div class="space-y-2">
                                <h4 class="text-neon font-black text-3xl italic">02</h4>
                                <h5 class="text-white font-bold text-lg uppercase">Easy Flow</h5>
                                <p class="text-sm text-gray-500">Alur booking instan tanpa ribet.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Court List Section -->
        <section id="courts" class="py-32 bg-dark relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="flex flex-col md:flex-row justify-between items-end mb-20 gap-8">
                    <div class="max-w-xl text-center md:text-left">
                        <h2 class="text-neon font-black uppercase tracking-[.3em] text-xs mb-4">Ready for battle?</h2>
                        <h3 class="text-6xl font-black text-white italic tracking-tighter uppercase font-heading">PICK YOUR <span class="underline decoration-neon underline-offset-8">ARENA</span></h3>
                    </div>
                    <p class="text-gray-500 font-bold max-w-xs uppercase text-right leading-tight tracking-widest text-xs border-r-4 border-neon pr-4">
                        CHOOSE FROM OUR PROFESSIONAL GRADE COURTS
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
                    @forelse($courts as $court)
                    <div class="bg-dark-card rounded-[2.5rem] overflow-hidden border border-white/5 hover:border-neon transition-all duration-500 group shadow-2xl hover:-translate-y-4">
                        <div class="aspect-[4/5] relative overflow-hidden bg-gray-900">
                            @if($court->photo)
                                <img src="{{ asset('storage/' . $court->photo) }}" alt="{{ $court->name }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700 brightness-75 group-hover:brightness-100">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-dark bg-neon opacity-20 italic font-black text-4xl">PADEL</div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-dark via-transparent to-transparent opacity-80"></div>
                            <div class="absolute bottom-8 left-8 right-8">
                                <div class="bg-neon text-dark inline-block px-4 py-1.5 rounded-lg text-sm font-black uppercase mb-4 shadow-lg">
                                    Rp {{ number_format($court->price_per_hour, 0, ',', '.') }}/h
                                </div>
                                <h4 class="text-3xl font-black text-white italic uppercase tracking-tighter">{{ $court->name }}</h4>
                            </div>
                        </div>
                        <div class="p-8">
                            <p class="text-gray-400 text-sm mb-8 line-clamp-2 italic font-medium">
                                {{ $court->description }}
                            </p>
                            
                            <div class="mb-10 flex flex-wrap gap-2 text-[10px] font-black uppercase tracking-widest text-white/40">
                                <span class="bg-dark px-3 py-1.5 rounded-lg border border-white/5">Digital Lighting</span>
                                <span class="bg-dark px-3 py-1.5 rounded-lg border border-white/5">Ultra Grip</span>
                            </div>

                            <a href="{{ route('customer.courts.show', $court) }}" class="block w-full text-center bg-white/5 border border-white/10 text-white py-5 rounded-2xl font-black uppercase tracking-tighter hover:bg-neon hover:text-dark transition-all shadow-xl">
                                Enter Arena
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full py-20 text-center border-2 border-dashed border-white/5 rounded-[3rem]">
                        <h3 class="text-2xl font-black text-white/20 italic tracking-tighter uppercase">No Arenas Active</h3>
                    </div>
                    @endforelse
                </div>
            </div>
            <!-- Striped Background Effect -->
            <div class="absolute inset-0 opacity-[0.02] pointer-events-none" style="background-image: repeating-linear-gradient(45deg, #bef264 0, #bef264 1px, transparent 0, transparent 50%)"></div>
        </section>

        <!-- Availability Section -->
        <section id="availability" class="py-32 bg-dark relative" x-data="availabilityCheck()">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 bg-dark-card p-16 rounded-[4rem] border border-white/5 shadow-[0_0_100px_rgba(0,0,0,0.5)] relative overflow-hidden">
                <div class="absolute top-0 right-0 p-8">
                    <div class="w-16 h-16 border-t-4 border-r-4 border-neon opacity-20"></div>
                </div>
                
                <div class="text-center mb-16">
                    <h2 class="text-white font-black italic text-5xl uppercase tracking-tighter mb-4 font-heading">SCHEDULE <span class="text-neon">SCANNER</span></h2>
                    <p class="text-gray-500 font-bold uppercase tracking-widest text-xs">Real-time availability monitoring</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mb-12">
                    <div class="space-y-3">
                        <label class="block text-xs font-black text-white/40 uppercase tracking-widest pl-2">Select Arena</label>
                        <select x-model="courtId" class="w-full bg-dark border-transparent rounded-2xl p-5 focus:ring-2 focus:ring-neon text-white font-bold uppercase tracking-tighter">
                            <option value="">-- Choose Court --</option>
                            @foreach($courts as $court)
                                <option value="{{ $court->id }}">{{ $court->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-3">
                        <label class="block text-xs font-black text-white/40 uppercase tracking-widest pl-2">Select Date</label>
                        <input type="date" x-model="date" min="{{ date('Y-m-d') }}" class="w-full bg-dark border-transparent rounded-2xl p-5 focus:ring-2 focus:ring-neon text-white font-bold uppercase tracking-tighter">
                    </div>
                </div>

                <div class="text-center mb-12">
                    <button @click="checkAvailability()" :disabled="isLoading" class="bg-neon text-dark px-12 py-5 rounded-2xl font-black uppercase tracking-tighter hover:scale-105 transition shadow-[0_0_30px_rgba(190,242,100,0.2)] disabled:opacity-50">
                        <span x-show="!isLoading">Scan Availability</span>
                        <span x-show="isLoading" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-dark" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Scanning...
                        </span>
                    </button>
                </div>

                <!-- Availability Result -->
                <div x-show="checked" x-cloak x-transition.opacity class="mt-16 pt-16 border-t border-white/5">
                    <h4 class="font-black text-white italic text-3xl uppercase tracking-tighter mb-10 text-center">SLOTS FOR <span class="text-neon" x-text="date"></span></h4>
                    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-6">
                        <template x-for="slot in slots" :key="slot.time">
                            <div class="p-5 rounded-[2rem] text-center font-black transition relative overflow-hidden group border border-transparent" 
                                 :class="slot.available ? 'bg-neon/5 text-neon border-neon/20 hover:bg-neon/10' : 'bg-red-500/5 text-red-500 border-red-500/20 opacity-40'">
                                <span class="text-lg italic" x-text="slot.time"></span>
                                <div class="text-[8px] uppercase tracking-[0.2em] font-black mt-2" x-text="slot.available ? 'Ready' : 'Taken'"></div>
                                <div x-show="slot.available" class="absolute -top-1 -right-1 w-2 h-2 bg-neon rounded-full animate-ping"></div>
                            </div>
                        </template>
                    </div>
                    <div class="mt-16 text-center">
                        @guest
                            <a href="{{ route('login') }}" class="inline-block bg-white text-dark px-12 py-4 rounded-2xl font-black uppercase tracking-tighter text-sm hover:bg-neon transition shadow-xl">Join to Reserve</a>
                        @else
                            <a :href="'/courts/' + courtId" class="inline-block bg-white text-dark px-12 py-4 rounded-2xl font-black uppercase tracking-tighter text-sm hover:bg-neon transition shadow-xl">Secure My Slot</a>
                        @endguest
                    </div>
                </div>
            </div>
        </section>

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
                        <li><a href="#about" class="hover:text-neon transition">Our Arena</a></li>
                        <li><a href="#courts" class="hover:text-neon transition">Court Specs</a></li>
                        <li><a href="#availability" class="hover:text-neon transition">Live Schedule</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-neon transition">Join Community</a></li>
                    </ul>
                </div>
            </div>
            <div class="max-w-7xl mx-auto border-t border-white/5 mt-32 pt-12 text-center text-[10px] font-black uppercase tracking-[0.5em] text-white/20">
                <p>&copy; 2026 PadelMate Indonesia. Built for the modern athlete.</p>
            </div>
        </footer>

        <script>
            function availabilityCheck() {
                return {
                    courtId: '',
                    date: '{{ date('Y-m-d') }}',
                    isLoading: false,
                    checked: false,
                    slots: [],
                    checkAvailability() {
                        if (!this.courtId) {
                            alert('Please select an arena scanner first.');
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
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    </body>
</html>
