<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pembayaran Booking</h2>
    </x-slot>

    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 0 0 rgba(99, 102, 241, 0.4); }
            50%       { box-shadow: 0 0 0 14px rgba(99, 102, 241, 0); }
        }
        @keyframes spin-slow {
            from { transform: rotate(0deg); }
            to   { transform: rotate(360deg); }
        }
        .fade-up { animation: fadeInUp 0.5s ease both; }
        .fade-up-2 { animation: fadeInUp 0.5s 0.15s ease both; }
        .fade-up-3 { animation: fadeInUp 0.5s 0.3s ease both; }
        .pay-btn { animation: pulse-glow 2s infinite; }
        .loading-spinner { animation: spin-slow 1s linear infinite; }
        .detail-row { border-bottom: 1px solid rgba(99,102,241,0.08); }
        .detail-row:last-child { border-bottom: none; }
    </style>

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-indigo-50 to-purple-50 py-12 px-4">
        <div class="max-w-lg mx-auto">

            {{-- Status Alert --}}
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-2xl text-red-700 text-sm flex items-center gap-2 fade-up">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    {{ session('error') }}
                </div>
            @endif

            {{-- Card --}}
            <div class="bg-white rounded-3xl shadow-2xl shadow-indigo-100/60 overflow-hidden fade-up">

                {{-- Top Banner --}}
                <div class="relative bg-gradient-to-r from-indigo-600 to-violet-600 px-8 pt-10 pb-16">
                    <div class="absolute inset-0 opacity-10"
                         style="background-image: url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"1\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');">
                    </div>
                    <div class="relative text-center">
                        <div class="inline-flex items-center justify-center w-14 h-14 bg-white/20 rounded-2xl mb-4 backdrop-blur-sm ring-1 ring-white/30">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        </div>
                        <h1 class="text-white font-extrabold text-2xl tracking-tight">Selesaikan Pembayaran</h1>
                        <p class="text-indigo-200 text-sm mt-1">Aman & terenkripsi via Midtrans</p>
                    </div>
                </div>

                {{-- Amount Badge --}}
                <div class="flex justify-center -mt-8 px-8 fade-up-2">
                    <div class="bg-white rounded-2xl shadow-xl shadow-indigo-100 px-8 py-4 text-center border border-indigo-50">
                        <p class="text-xs font-semibold text-indigo-500 uppercase tracking-widest mb-1">Total Tagihan</p>
                        <p class="text-4xl font-black text-gray-900">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                    </div>
                </div>

                {{-- Booking Details --}}
                <div class="px-8 pt-6 pb-2 fade-up-2">
                    <div class="bg-slate-50 rounded-2xl overflow-hidden divide-y divide-slate-100">
                        <div class="flex items-center justify-between px-5 py-3.5 detail-row">
                            <span class="text-sm text-slate-500 flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                                ID Booking
                            </span>
                            <span class="text-sm font-bold text-slate-800">#{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="flex items-center justify-between px-5 py-3.5 detail-row">
                            <span class="text-sm text-slate-500 flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                Lapangan
                            </span>
                            <span class="text-sm font-bold text-slate-800">{{ $booking->court->name }}</span>
                        </div>
                        <div class="flex items-center justify-between px-5 py-3.5 detail-row">
                            <span class="text-sm text-slate-500 flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Tanggal
                            </span>
                            <span class="text-sm font-bold text-slate-800">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</span>
                        </div>
                        <div class="flex items-center justify-between px-5 py-3.5">
                            <span class="text-sm text-slate-500 flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Jam Main
                            </span>
                            <span class="text-sm font-bold text-slate-800">{{ substr($booking->start_time, 0, 5) }} – {{ substr($booking->end_time, 0, 5) }} WIB</span>
                        </div>
                    </div>
                </div>

                {{-- Pay Button --}}
                <div class="px-8 pt-6 pb-8 fade-up-3 space-y-3">
                    <button id="pay-button"
                        class="pay-btn w-full bg-gradient-to-r from-indigo-600 to-violet-600 text-white py-4 px-6 rounded-2xl font-black text-base tracking-wide hover:from-indigo-700 hover:to-violet-700 active:scale-95 transition-all duration-200 flex items-center justify-center gap-3 group">
                        <svg id="btn-icon" class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        <span id="btn-text">BAYAR SEKARANG</span>
                    </button>

                    {{-- Trust Badges --}}
                    <div class="flex items-center justify-center gap-4 pt-1">
                        <span class="flex items-center gap-1 text-xs text-slate-400">
                            <svg class="w-3.5 h-3.5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            SSL Secured
                        </span>
                        <span class="text-slate-200">|</span>
                        <span class="flex items-center gap-1 text-xs text-slate-400">
                            <svg class="w-3.5 h-3.5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/></svg>
                            Multi-method Payment
                        </span>
                        <span class="text-slate-200">|</span>
                        <span class="flex items-center gap-1 text-xs text-slate-400">
                            <svg class="w-3.5 h-3.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            Instant Confirmation
                        </span>
                    </div>

                    {{-- Divider --}}
                    <div class="relative flex items-center py-2">
                        <div class="flex-grow border-t border-slate-100"></div>
                        <span class="flex-shrink mx-4 text-slate-300 text-xs uppercase tracking-widest">atau</span>
                        <div class="flex-grow border-t border-slate-100"></div>
                    </div>

                    {{-- Manual Upload --}}
                    <form action="{{ route('customer.payments.store', $booking) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <label for="proof_of_payment" class="relative flex items-center gap-3 p-4 border border-dashed border-slate-200 rounded-2xl cursor-pointer hover:bg-slate-50 hover:border-indigo-300 transition group">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 group-hover:bg-indigo-50 flex items-center justify-center flex-shrink-0 transition">
                                <svg class="w-5 h-5 text-slate-400 group-hover:text-indigo-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-slate-700">Upload Bukti Transfer Manual</p>
                                <p class="text-xs text-slate-400 truncate" id="filename-display">PNG, JPG, JPEG hingga 2MB</p>
                            </div>
                            <input type="file" name="proof_of_payment" id="proof_of_payment" class="hidden">
                        </label>
                        <button type="submit" class="mt-2 w-full bg-slate-100 text-slate-600 py-3 px-4 rounded-xl font-semibold text-sm hover:bg-slate-200 active:scale-95 transition-all duration-150">
                            Kirim Bukti
                        </button>
                    </form>

                    <a href="{{ route('dashboard') }}" class="flex items-center justify-center gap-1.5 text-xs text-slate-400 hover:text-slate-600 transition pt-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        Kembali ke Dashboard
                    </a>
                </div>
            </div>

            <p class="text-center text-xs text-slate-400 mt-6">
                Powered by <span class="font-semibold text-indigo-400">Midtrans</span> &bull; Transaksi aman & terenkripsi
            </p>
        </div>
    </div>

    <!-- Midtrans Snap JS -->
    <script src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script>
        const payButton = document.getElementById('pay-button');
        const btnText   = document.getElementById('btn-text');
        const btnIcon   = document.getElementById('btn-icon');

        payButton.addEventListener('click', function () {
            // Show loading state
            btnText.textContent = 'Menghubungkan...';
            btnIcon.outerHTML = '<svg id="btn-icon" class="w-5 h-5 loading-spinner" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>';

            window.snap.pay('{{ $payment->snap_token }}', {
                onSuccess: function (result) {
                    window.location.href = "{{ route('dashboard') }}?status=success";
                },
                onPending: function (result) {
                    window.location.href = "{{ route('dashboard') }}?status=pending";
                },
                onError: function (result) {
                    btnText.textContent = 'BAYAR SEKARANG';
                    document.getElementById('btn-icon').classList.remove('loading-spinner');
                    alert('Pembayaran gagal! Silakan coba lagi.');
                },
                onClose: function () {
                    btnText.textContent = 'BAYAR SEKARANG';
                    document.getElementById('btn-icon')?.classList.remove('loading-spinner');
                }
            });
        });

        document.getElementById('proof_of_payment').addEventListener('change', function(e) {
            if (e.target.files.length > 0) {
                document.getElementById('filename-display').textContent = e.target.files[0].name;
            }
        });
    </script>
</x-app-layout>
