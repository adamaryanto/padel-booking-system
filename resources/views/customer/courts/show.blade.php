<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Booking: {{ $court->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Court Info -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            @if($court->photo)
                                <img src="{{ asset('storage/' . $court->photo) }}" alt="{{ $court->name }}" class="w-full h-80 object-cover rounded-xl mb-6">
                            @endif
                            <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ $court->name }}</h3>
                            <p class="text-gray-600 leading-relaxed mb-6">{{ $court->description }}</p>
                            
                            <div class="border-t border-gray-100 pt-6">
                                <h4 class="font-semibold text-gray-900 mb-4">Fasilitas & Informasi:</h4>
                                <ul class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Lapangan Standar WPT
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Penerangan Malam
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Harga Rp. {{ number_format($court->price_per_hour, 0, ',', '.') }}/jam
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booking Form -->
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg sticky top-24">
                        <div class="p-6">
                            <h4 class="font-bold text-gray-900 mb-6">Pilih Jadwal Bermain</h4>

                            @if($errors->has('error'))
                                <div class="bg-red-50 text-red-700 p-4 rounded-xl mb-6 text-sm">
                                    {{ $errors->first('error') }}
                                </div>
                            @endif

                            <form action="{{ route('customer.bookings.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="court_id" value="{{ $court->id }}">
                                
                                <div class="space-y-4">
                                    <div>
                                        <label for="booking_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                                        <input type="date" name="booking_date" id="booking_date" min="{{ date('Y-m-d') }}" class="block w-full border-gray-300 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500" value="{{ old('booking_date', date('Y-m-d')) }}" required>
                                    </div>

                                    <div>
                                        <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">Jam Mulai</label>
                                        <select name="start_time" id="start_time" class="block w-full border-gray-300 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                            @for($i = 6; $i <= 22; $i++)
                                                <option value="{{ sprintf('%02d:00', $i) }}" {{ old('start_time') == sprintf('%02d:00', $i) ? 'selected' : '' }}>{{ sprintf('%02d:00', $i) }}</option>
                                            @endfor
                                        </select>
                                    </div>

                                    <div>
                                        <label for="duration" class="block text-sm font-medium text-gray-700 mb-1">Durasi (Jam)</label>
                                        <select name="duration" id="duration" class="block w-full border-gray-300 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                            <option value="1" {{ old('duration') == '1' ? 'selected' : '' }}>1 Jam</option>
                                            <option value="2" {{ old('duration') == '2' ? 'selected' : '' }}>2 Jam</option>
                                            <option value="3" {{ old('duration') == '3' ? 'selected' : '' }}>3 Jam</option>
                                        </select>
                                    </div>

                                    <div class="pt-6 border-t border-gray-100">
                                        <div class="flex justify-between mb-4">
                                            <span class="text-gray-600">Total Harga</span>
                                            <span class="font-bold text-indigo-600" id="display-total">Rp. {{ number_format($court->price_per_hour, 0, ',', '.') }}</span>
                                        </div>
                                        <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                                            Booking Sekarang
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const durationSelect = document.getElementById('duration');
        const displayTotal = document.getElementById('display-total');
        const pricePerHour = {{ $court->price_per_hour }};

        durationSelect.addEventListener('change', function() {
            const total = pricePerHour * this.value;
            displayTotal.innerText = 'Rp. ' + new Intl.NumberFormat('id-ID').format(total);
        });
    </script>
</x-app-layout>
