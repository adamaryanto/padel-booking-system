@extends('layouts.customer')

@section('title', 'Riwayat Booking')
@section('header', 'Riwayat Booking Saya')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        @if(session('success'))
            <div class="bg-green-50 text-green-700 p-4 rounded-xl mb-6 flex items-center dark:bg-green-900/20 dark:text-green-400 border border-green-100 dark:border-green-800">
                <i data-lucide="check-circle" class="w-5 h-5 mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-bold">#</th>
                        <th scope="col" class="px-6 py-4 font-bold">Lapangan</th>
                        <th scope="col" class="px-6 py-4 font-bold">Jadwal</th>
                        <th scope="col" class="px-6 py-4 font-bold">Total Harga</th>
                        <th scope="col" class="px-6 py-4 font-bold text-center">Status</th>
                        <th scope="col" class="px-6 py-4 font-bold text-center">Pembayaran</th>
                        <th scope="col" class="px-6 py-4 font-bold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($bookings as $booking)
                    <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">{{ $booking->court->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                            <div class="flex flex-col">
                                <span class="font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</span>
                                <span class="text-xs text-gray-500">{{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }} WIB</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-black text-indigo-600 dark:text-indigo-400">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($booking->status == 'pending')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-tighter bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-500 border border-yellow-200 dark:border-yellow-800">
                                    {{ $booking->status }}
                                </span>
                            @elseif($booking->status == 'approved')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-tighter bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-500 border border-green-200 dark:border-green-800">
                                    {{ $booking->status }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-tighter bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-500 border border-red-200 dark:border-red-800">
                                    {{ $booking->status }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($booking->payment)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-tighter
                                    @if($booking->payment->status == 'verified') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-500 border border-green-200 dark:border-green-800
                                    @elseif($booking->payment->status == 'rejected') bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-500 border border-red-200 dark:border-red-800
                                    @elseif($booking->payment->status == 'pending' && $booking->payment->snap_token) bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-500 border border-blue-200 dark:border-blue-800
                                    @else bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400 border border-gray-200 dark:border-gray-600 @endif">
                                    {{ $booking->payment->status == 'pending' ? 'Belum Bayar' : $booking->payment->status }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-tighter bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400 border border-gray-200 dark:border-gray-600">Terbuka</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end space-x-2">
                                @if($booking->status == 'pending')
                                    @if($booking->payment && $booking->payment->snap_token)
                                        <button onclick="payBooking('{{ $booking->payment->snap_token }}')" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:scale-95 transition shadow-lg shadow-indigo-100 dark:shadow-none">
                                            <i data-lucide="credit-card" class="w-4 h-4 mr-1.5"></i>
                                            Bayar
                                        </button>
                                    @endif
                                    <a href="{{ route('customer.payments.create', $booking) }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 underline underline-offset-4 decoration-dotted">Detail</a>
                                @else
                                    <span class="text-xs font-bold text-gray-400 italic">Selesai</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-20 text-center">
                            <div class="flex flex-col items-center">
                                <i data-lucide="calendar-x" class="w-16 h-16 text-gray-200 dark:text-gray-700 mb-4"></i>
                                <p class="text-gray-500 dark:text-gray-400 font-bold uppercase tracking-widest text-xs">Anda belum memiliki riwayat booking.</p>
                                <a href="{{ route('welcome') }}" class="mt-4 bg-indigo-50 text-indigo-700 px-6 py-2 rounded-xl font-black uppercase tracking-tighter hover:bg-indigo-100 transition dark:bg-indigo-900/30 dark:text-indigo-400 dark:hover:bg-indigo-900/50">Cari Lapangan</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
    <!-- Midtrans Snap JS -->
    <script src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script type="text/javascript">
        function payBooking(snapToken) {
            window.snap.pay(snapToken, {
                onSuccess: function (result) {
                    window.location.reload();
                },
                onPending: function (result) {
                    window.location.reload();
                },
                onError: function (result) {
                    alert("Pembayaran gagal!");
                },
                onClose: function () {
                    console.log('User closed the popup');
                }
            });
        }

        // Auto-popup logic if redirected with ?pay=token
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            const payToken = urlParams.get('pay');
            if (payToken) {
                payBooking(payToken);
            }
            
            // Re-initialize Lucide in case
            if(window.lucide) {
                lucide.createIcons();
            }
        };
    </script>
@endpush
@endsection

