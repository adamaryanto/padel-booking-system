@extends('layouts.public')

@section('title', 'Arena Detail')

@section('content')
<div class="py-24 px-4 sm:px-6 lg:px-8 bg-dark min-h-screen">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row gap-12">
            <!-- Court Info -->
            <div class="md:w-2/3">
                <div class="bg-dark-card rounded-[3rem] overflow-hidden border border-white/5 shadow-2xl group">
                    <div class="aspect-video relative overflow-hidden bg-gray-900 border-b border-white/5">
                        @if($court->photo)
                            <img src="{{ asset('storage/' . $court->photo) }}" alt="{{ $court->name }}" class="w-full h-full object-cover brightness-75 group-hover:brightness-100 transition duration-700">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-dark bg-neon opacity-20 italic font-black text-6xl">PADEL</div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-dark/80 via-transparent to-transparent"></div>
                        <div class="absolute bottom-8 left-8">
                            <h2 class="text-5xl font-black text-white italic tracking-tighter uppercase font-heading">{{ $court->name }}</h2>
                        </div>
                    </div>
                    
                    <div class="p-10">
                        <p class="text-gray-400 text-xl mb-12 leading-relaxed font-medium italic">
                            {{ $court->description }}
                        </p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
                            <div class="flex items-center space-x-4 p-6 bg-dark rounded-2xl border border-white/5">
                                <div class="bg-neon/10 p-3 rounded-xl text-neon">
                                    <i class="fas fa-check"></i>
                                </div>
                                <span class="text-white font-bold uppercase tracking-widest text-xs">Standard WPT Arena</span>
                            </div>
                            <div class="flex items-center space-x-4 p-6 bg-dark rounded-2xl border border-white/5">
                                <div class="bg-neon/10 p-3 rounded-xl text-neon">
                                    <i class="fas fa-lightbulb"></i>
                                </div>
                                <span class="text-white font-bold uppercase tracking-widest text-xs">Premium Lighting</span>
                            </div>
                        </div>

                        <div class="p-8 rounded-3xl bg-neon/10 border border-neon/20 flex flex-col items-center text-center">
                            <span class="text-neon font-black uppercase tracking-[0.3em] text-[10px] mb-2">Current Rate</span>
                            <h2 class="text-4xl font-black text-white italic tracking-tighter">
                                Rp {{ number_format($court->price_per_hour, 0, ',', '.') }} <span class="text-neon text-xl uppercase not-italic">/ Hour</span>
                            </h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Form -->
            <div class="md:w-1/3">
                <div class="bg-dark-card rounded-[3rem] border border-white/5 p-10 shadow-2xl sticky top-24">
                    <h3 class="text-white font-black italic text-2xl uppercase tracking-tighter mb-8 font-heading">RESERVE <span class="text-neon">SLOT</span></h3>
                    
                    @if($errors->has('error'))
                        <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-500 px-4 py-3 rounded-xl font-bold text-xs uppercase tracking-widest animate-pulse">
                            <i class="fas fa-exclamation-triangle mr-2"></i> {{ $errors->first('error') }}
                        </div>
                    @endif

                    <form action="{{ route('customer.bookings.store') }}" method="POST" id="bookingFormMain" class="space-y-6">
                        @csrf
                        <input type="hidden" name="court_id" value="{{ $court->id }}">
                        
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-white/40 uppercase tracking-widest ml-2">Select Date</label>
                            <input type="date" name="booking_date" id="booking_date" min="{{ date('Y-m-d') }}" class="w-full bg-dark border-transparent rounded-2xl p-4 focus:ring-2 focus:ring-neon text-white font-bold uppercase tracking-tighter" value="{{ old('booking_date', date('Y-m-d')) }}" required>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-white/40 uppercase tracking-widest ml-2">Start</label>
                                <select name="start_time" id="start_time" class="w-full bg-dark border-transparent rounded-2xl p-4 focus:ring-2 focus:ring-neon text-white font-bold uppercase tracking-tighter custom-select" required>
                                    @for($i = 6; $i <= 22; $i++)
                                        <option value="{{ sprintf('%02d:00', $i) }}" {{ old('start_time') == sprintf('%02d:00', $i) ? 'selected' : '' }}>{{ sprintf('%02d:00', $i) }}:00</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-white/40 uppercase tracking-widest ml-2">Duration</label>
                                <select name="duration" id="duration" class="w-full bg-dark border-transparent rounded-2xl p-4 focus:ring-2 focus:ring-neon text-white font-bold uppercase tracking-tighter custom-select" required>
                                    <option value="1" {{ old('duration') == '1' ? 'selected' : '' }}>1 Hr</option>
                                    <option value="2" {{ old('duration') == '2' ? 'selected' : '' }}>2 Hrs</option>
                                    <option value="3" {{ old('duration') == '3' ? 'selected' : '' }}>3 Hrs</option>
                                </select>
                            </div>
                        </div>

                        <div class="p-6 rounded-2xl bg-white/5 border border-white/5 flex justify-between items-center mt-8">
                            <span class="text-[10px] font-black text-white/40 uppercase tracking-widest">Total</span>
                            <h4 class="text-2xl font-black text-neon italic tracking-tighter m-0" id="display-total">Rp {{ number_format($court->price_per_hour, 0, ',', '.') }}</h4>
                        </div>

                        <button type="submit" id="submitBookingBtn" class="w-full bg-neon text-dark py-5 rounded-2xl font-black uppercase tracking-tighter text-xl hover:bg-white transition shadow-2xl active:scale-95 transform">
                            BOOK NOW <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal Overlay -->
<div id="successModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 bg-dark/90 backdrop-blur-sm" x-cloak>
    <div class="bg-dark-card border border-white/10 rounded-[3rem] p-12 max-w-md w-full text-center shadow-[0_0_100px_rgba(190,242,100,0.1)]">
        <div class="mb-8 w-24 h-24 bg-neon rounded-full flex items-center justify-center mx-auto shadow-[0_0_30px_rgba(190,242,100,0.4)] animate-bounce">
            <i class="fas fa-check text-dark text-4xl"></i>
        </div>
        <h3 class="text-4xl font-black text-white italic tracking-tighter uppercase mb-4">SECURED!</h3>
        <p class="text-gray-400 font-medium mb-10 leading-relaxed">Your arena is locked. We've sent a confirmation to your email.</p>
        
        <div class="flex flex-col space-y-4">
            <a id="downloadReceiptBtn" href="#" target="_blank" class="w-full bg-white text-dark py-4 rounded-2xl font-black uppercase tracking-tighter hover:bg-neon transition shadow-xl">
                <i class="fas fa-file-invoice mr-2"></i>Download Receipt
            </a>
            <a href="{{ route('dashboard') }}" class="text-gray-500 font-black uppercase text-xs tracking-widest hover:text-white transition">
                Return to History
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
    $(document).ready(function() {
        const $bookingForm = $('#bookingFormMain');
        const $bookingBtn = $('#submitBookingBtn');
        const originalBtnText = $bookingBtn.text();
        const $durationSelect = $('#duration');
        const $displayTotal = $('#display-total');
        const pricePerHour = {{ $court->price_per_hour }};
        const $successModal = $('#successModal');
        const $downloadBtn = $('#downloadReceiptBtn');

        $durationSelect.on('change', function() {
            const total = pricePerHour * $(this).val();
            $displayTotal.text('Rp ' + new Intl.NumberFormat('id-ID').format(total));
        });

        $bookingForm.on('submit', async function(e) {
            e.preventDefault();
            
            $bookingBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> PROCESSING...');

            const formData = new FormData(this);

            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.message || result.errors?.error?.[0] || 'Something went wrong');
                }

                if (result.snap_token) {
                    window.snap.pay(result.snap_token, {
                        onSuccess: function (midtransResult) {
                            $downloadBtn.attr('href', `/bookings/${result.booking_id}/receipt`);
                            $successModal.removeClass('hidden');
                        },
                        onPending: function (midtransResult) {
                            window.location.href = "{{ route('dashboard') }}?status=pending";
                        },
                        onError: function (midtransResult) {
                            alert("Payment failed!");
                            window.location.href = "{{ route('dashboard') }}";
                        },
                        onClose: function () {
                            alert("Booking saved. Please complete payment in My Bookings.");
                            window.location.href = "{{ route('dashboard') }}";
                        }
                    });
                } else {
                    window.location.href = "{{ route('dashboard') }}?success=true";
                }

            } catch (error) {
                alert(error.message);
            } finally {
                $bookingBtn.prop('disabled', false).html(originalBtnText);
            }
        });
    });
</script>
@endpush
