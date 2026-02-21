@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('header', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="p-4 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700 flex items-center">
        <div class="p-3 rounded-lg bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 me-4">
            <i data-lucide="calendar-check" class="w-6 h-6"></i>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Booking Hari Ini</p>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalBookingToday }}</h3>
        </div>
    </div>
    <div class="p-4 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700 flex items-center">
        <div class="p-3 rounded-lg bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400 me-4">
            <i data-lucide="banknote" class="w-6 h-6"></i>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pendapatan</p>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Rp. {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
        </div>
    </div>
    <div class="p-4 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700 flex items-center">
        <div class="p-3 rounded-lg bg-orange-50 dark:bg-orange-900/20 text-orange-600 dark:text-orange-400 me-4">
            <i data-lucide="clock-alert" class="w-6 h-6"></i>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Menunggu Verifikasi</p>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $pendingVerification }}</h3>
        </div>
    </div>
    <div class="p-4 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700 flex items-center">
        <div class="p-3 rounded-lg bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400 me-4">
            <i data-lucide="stadium" class="w-6 h-6"></i>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Lapangan</p>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalCourts }}</h3>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="lg:col-span-2 p-4 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Statistik Booking Mingguan</h3>
            <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">7 Hari Terakhir</span>
        </div>
        <div id="booking-chart" class="h-64"></div>
    </div>
    
    <div class="p-4 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Akses Cepat</h3>
        <div class="space-y-3">
            <a href="{{ route('admin.bookings.index') }}" class="flex items-center p-3 text-base font-medium text-gray-900 rounded-lg bg-gray-50 hover:bg-gray-100 group dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">
                <i data-lucide="plus-circle" class="w-5 h-5 text-gray-500 dark:text-gray-400"></i>
                <span class="ms-3">Kelola Booking</span>
            </a>
            <a href="{{ route('admin.courts.index') }}" class="flex items-center p-3 text-base font-medium text-gray-900 rounded-lg bg-gray-50 hover:bg-gray-100 group dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">
                <i data-lucide="settings" class="w-5 h-5 text-gray-500 dark:text-gray-400"></i>
                <span class="ms-3">Edit Lapangan</span>
            </a>
        </div>
    </div>
</div>

<div class="p-4 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Booking Terbaru</h3>
        <a href="{{ route('admin.bookings.index') }}" class="text-sm font-medium text-primary-600 hover:underline dark:text-primary-500">Lihat Semua</a>
    </div>
    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">Pelanggan</th>
                    <th scope="col" class="px-6 py-3">Lapangan</th>
                    <th scope="col" class="px-6 py-3">Jadwal</th>
                    <th scope="col" class="px-6 py-3 text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($latestBookings as $booking)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $booking->user->name }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $booking->court->name }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col">
                            <span>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</span>
                            <span class="text-xs text-gray-400">{{ substr($booking->start_time, 0, 5) }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($booking->status == 'pending')
                            <span class="bg-orange-100 text-orange-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-orange-900/30 dark:text-orange-500 border border-orange-200 dark:border-orange-800">Pending</span>
                        @elseif($booking->status == 'approved')
                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-green-900/30 dark:text-green-500 border border-green-200 dark:border-green-800">Approved</span>
                        @else
                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-red-900/30 dark:text-red-500 border border-red-200 dark:border-red-800">Cancelled</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <td colspan="4" class="px-6 py-4 text-center">Belum ada data booking.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Data booking mingguan (contoh, idealnya ditarik dari backend)
        @php
            $defaultData = [0, 0, 0, 0, 0, 0, 0];
            $defaultDays = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
        @endphp
        const bookingData = @json($weeklyBookingData ?? $defaultData);
        const days = @json($weeklyBookingDays ?? $defaultDays);

        var options = {
            series: [{
                name: 'Total Booking',
                data: bookingData
            }],
            chart: {
                type: 'area',
                height: 250,
                toolbar: { show: false },
                fontFamily: 'Inter, sans-serif'
            },
            dataLabels: { enabled: false },
            stroke: {
                curve: 'smooth',
                width: 3,
                colors: ['#84cc16']
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.45,
                    opacityTo: 0.05,
                    stops: [20, 100],
                    colorStops: [
                        { offset: 0, color: '#84cc16', opacity: 0.4 },
                        { offset: 100, color: '#84cc16', opacity: 0 }
                    ]
                }
            },
            xaxis: {
                categories: days,
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: { show: false },
            grid: {
                show: true,
                borderColor: '#f1f1f1',
                strokeDashArray: 4,
            },
            markers: {
                size: 4,
                colors: ['#84cc16'],
                strokeColors: '#fff',
                strokeWidth: 2,
                hover: { size: 7 }
            },
            tooltip: {
                theme: 'dark'
            }
        };

        var chart = new ApexCharts(document.querySelector("#booking-chart"), options);
        chart.render();
        
        // Initializing Lucide icons in case they weren't
        if(window.lucide) {
            lucide.createIcons();
        }
    });
</script>
@endpush
