@extends('layouts.admin')

@section('title', 'Daftar Booking')
@section('header', 'Manajemen Booking & Pembayaran')

@section('content')
<div class="space-y-6">
    @if(session('success'))
        <div id="alert-success" class="flex items-center p-4 mb-4 text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 border border-green-200 dark:border-green-800" role="alert">
            <i data-lucide="check-circle" class="flex-shrink-0 w-4 h-4"></i>
            <span class="sr-only">Success</span>
            <div class="ms-3 text-sm font-medium">
                {{ session('success') }}
            </div>
            <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-green-400 dark:hover:bg-gray-700" data-dismiss-target="#alert-success" aria-label="Close">
                <span class="sr-only">Close</span>
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
    @endif

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg p-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between pb-4 bg-white dark:bg-gray-800">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Seluruh Riwayat Booking</h3>
        </div>

        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">#</th>
                    <th scope="col" class="px-6 py-3">Pelanggan</th>
                    <th scope="col" class="px-6 py-3">Lapangan</th>
                    <th scope="col" class="px-6 py-3">Jadwal</th>
                    <th scope="col" class="px-6 py-3">Total Harga</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                    <th scope="col" class="px-6 py-3">Pembayaran</th>
                    <th scope="col" class="px-6 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-6 py-4">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $booking->user->name }}</td>
                    <td class="px-6 py-4">{{ $booking->court->name }}</td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col">
                            <span class="font-semibold">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">Rp. {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        @if($booking->status == 'pending')
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-yellow-900/30 dark:text-yellow-500 border border-yellow-200 dark:border-yellow-800">Pending</span>
                        @elseif($booking->status == 'approved')
                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-green-900/30 dark:text-green-500 border border-green-200 dark:border-green-800">Approved</span>
                        @else
                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-red-900/30 dark:text-red-500 border border-red-200 dark:border-red-800">Cancelled</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($booking->payment)
                            <div class="flex flex-col space-y-1">
                                <span class="text-xs font-medium px-2 py-0.5 rounded-full border text-center
                                    @if($booking->payment->status == 'verified') bg-green-100 text-green-800 border-green-200 dark:bg-green-900/30 dark:text-green-500 dark:border-green-800
                                    @elseif($booking->payment->status == 'rejected') bg-red-100 text-red-800 border-red-200 dark:bg-red-900/30 dark:text-red-500 dark:border-red-800
                                    @else bg-yellow-100 text-yellow-800 border-yellow-200 dark:bg-yellow-900/30 dark:text-yellow-500 dark:border-yellow-800 @endif uppercase text-xs">
                                    {{ ucfirst($booking->payment->status) }}
                                </span>
                                @if($booking->payment->proof_of_payment)
                                    <a href="{{ asset('storage/' . $booking->payment->proof_of_payment) }}" target="_blank" class="text-[10px] text-primary-600 hover:underline inline-flex items-center">
                                        <i data-lucide="external-link" class="w-3 h-3 me-1"></i> Lihat Bukti
                                    </a>
                                @endif
                            </div>
                        @else
                            <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-full border border-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">Belum Bayar</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end space-x-2">
                            @if($booking->status == 'pending')
                                <form action="{{ route('admin.bookings.approve', $booking) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-white border border-green-600 hover:bg-green-600 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-xs p-1.5 text-center dark:border-green-500 dark:text-green-500 dark:hover:text-white dark:hover:bg-green-600 dark:focus:ring-green-800" title="Approve Booking">
                                        <i data-lucide="check" class="w-4 h-4"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.bookings.cancel', $booking) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-red-600 hover:text-white border border-red-600 hover:bg-red-600 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-xs p-1.5 text-center dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-800" title="Cancel Booking">
                                        <i data-lucide="x" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            @endif

                            @if($booking->payment && $booking->payment->status == 'pending')
                                <form action="{{ route('admin.payments.verify', $booking->payment) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-blue-600 hover:text-white border border-blue-600 hover:bg-blue-600 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-xs p-1.5 text-center dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-600 dark:focus:ring-blue-800" title="Verify Payment">
                                        <i data-lucide="banknote" class="w-4 h-4"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.payments.reject', $booking->payment) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-orange-600 hover:text-white border border-orange-600 hover:bg-orange-600 focus:ring-4 focus:outline-none focus:ring-orange-300 font-medium rounded-lg text-xs p-1.5 text-center dark:border-orange-500 dark:text-orange-500 dark:hover:text-white dark:hover:bg-orange-600 dark:focus:ring-orange-800" title="Reject Payment">
                                        <i data-lucide="octagon-minus" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <td colspan="8" class="px-6 py-4 text-center text-gray-400 italic">Belum ada booking.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
