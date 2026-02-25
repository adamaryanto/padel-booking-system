@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('header', 'Dashboard Overview')

@section('content')
<div class="row mt-4">
    <!-- Booking Today -->
    <div class="col-lg-4 col-sm-6 mb-4">
        <div class="small-box bg-info shadow border-0 h-100 rounded-lg overflow-hidden">
            <div class="inner p-3">
                <h3 class="font-weight-bold mb-1" style="font-size: 1.8rem;">{{ $totalBookingToday }}</h3>
                <p class="text-uppercase small font-weight-bold tracking-wider mb-0 opacity-75">Booking Hari Ini</p>
            </div>
            <div class="icon" style="top: 10px; right: 15px; opacity: 0.25;">
                <i class="fas fa-calendar-check fa-2x"></i>
            </div>
            <a href="{{ route('admin.bookings.index') }}" class="small-box-footer bg-dark-50 py-2">
                Selengkapnya <i class="fas fa-arrow-circle-right ml-1"></i>
            </a>
        </div>
    </div>

    <!-- Court Revenue -->
    <div class="col-lg-4 col-sm-6 mb-4">
        <div class="small-box bg-success shadow border-0 h-100 rounded-lg overflow-hidden">
            <div class="inner p-3">
                <h3 class="font-weight-bold mb-1" style="font-size: 1.8rem;"><sup style="font-size: 18px">Rp</sup>{{ number_format($courtRevenue/1000, 0, ',', '.') }}<small class="text-white-50 ml-1">k</small></h3>
                <p class="text-uppercase small font-weight-bold tracking-wider mb-0 opacity-75">Pendapatan Lapangan</p>
            </div>
            <div class="icon" style="top: 10px; right: 15px; opacity: 0.25;">
                <i class="fas fa-table-tennis-paddle-ball fa-2x"></i>
            </div>
            <a href="{{ route('admin.bookings.index') }}" class="small-box-footer bg-dark-50 py-2">
                Lihat Detail <i class="fas fa-arrow-circle-right ml-1"></i>
            </a>
        </div>
    </div>

    <!-- Membership Revenue -->
    <div class="col-lg-4 col-sm-6 mb-4">
        <div class="small-box shadow border-0 h-100 rounded-lg overflow-hidden text-white" style="background: linear-gradient(135deg, #6610f2, #6f42c1);">
            <div class="inner p-3">
                <h3 class="font-weight-bold mb-1" style="font-size: 1.8rem;"><sup style="font-size: 18px">Rp</sup>{{ number_format($membershipRevenue/1000, 0, ',', '.') }}<small class="text-white-50 ml-1">k</small></h3>
                <p class="text-uppercase small font-weight-bold tracking-wider mb-0 opacity-75">Pendapatan Member</p>
            </div>
            <div class="icon" style="top: 10px; right: 15px; opacity: 0.25;">
                <i class="fas fa-id-card fa-2x"></i>
            </div>
            <a href="{{ route('admin.bookings.index') }}" class="small-box-footer bg-dark-50 py-2">
                Lihat Detail <i class="fas fa-arrow-circle-right ml-1"></i>
            </a>
        </div>
    </div>
</div>

<div class="row mt-2">
    <!-- Monthly Total -->
    <div class="col-lg-4 col-sm-6 mb-4">
        <div class="small-box bg-dark shadow border-0 h-100 rounded-lg overflow-hidden">
            <div class="inner p-3">
                <h3 class="font-weight-bold mb-1" style="font-size: 1.8rem;"><sup style="font-size: 18px">Rp</sup>{{ number_format($monthlyTotalRevenue/1000, 0, ',', '.') }}<small class="text-white-50 ml-1">k</small></h3>
                <p class="text-uppercase small font-weight-bold tracking-wider mb-0 opacity-75">Akumulasi Bulan Ini</p>
            </div>
            <div class="icon" style="top: 10px; right: 15px; opacity: 0.25;">
                <i class="fas fa-chart-line fa-2x"></i>
            </div>
            <div class="small-box-footer bg-dark-50 py-2 text-white-50 italic small">
                Total Pendapatan Terverifikasi
            </div>
        </div>
    </div>

    <!-- Pending Verification -->
    <div class="col-lg-4 col-sm-6 mb-4">
        <div class="small-box bg-warning shadow border-0 h-100 rounded-lg overflow-hidden text-white">
            <div class="inner p-3">
                <h3 class="font-weight-bold mb-1 text-white" style="font-size: 1.8rem;">{{ $pendingVerification }}</h3>
                <p class="text-uppercase small font-weight-bold tracking-wider mb-0 opacity-100" style="color: rgba(255,255,255,0.9);">Pending Verifikasi</p>
            </div>
            <div class="icon" style="top: 10px; right: 15px; opacity: 0.25;">
                <i class="fas fa-clock fa-2x text-white"></i>
            </div>
            <a href="{{ route('admin.bookings.index') }}?status=pending" class="small-box-footer bg-dark-50 py-2 text-white">
                Cek Booking & Member <i class="fas fa-arrow-circle-right ml-1 text-white"></i>
            </a>
        </div>
    </div>

    <!-- Total Courts -->
    <div class="col-lg-4 col-sm-6 mb-4">
        <div class="small-box bg-danger shadow border-0 h-100 rounded-lg overflow-hidden">
            <div class="inner p-3">
                <h3 class="font-weight-bold mb-1" style="font-size: 1.8rem;">{{ $totalCourts }}</h3>
                <p class="text-uppercase small font-weight-bold tracking-wider mb-0 opacity-75">Tersedia Lapangan</p>
            </div>
            <div class="icon" style="top: 10px; right: 15px; opacity: 0.25;">
                <i class="fas fa-border-all fa-2x"></i>
            </div>
            <a href="{{ route('admin.courts.index') }}" class="small-box-footer bg-dark-50 py-2">
                Kelola Assets <i class="fas fa-arrow-circle-right ml-1"></i>
            </a>
        </div>
    </div>
</div>

<div class="row mt-2">
    <div class="col-lg-8">
        <div class="card card-outline card-primary shadow-sm">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-chart-line mr-1 text-primary"></i>
                    Statistik Booking Mingguan
                </h3>
            </div>
            <div class="card-body">
                <div id="booking-chart-lte" style="height: 350px;"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card card-outline card-primary shadow-sm">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-bolt mr-1 text-primary"></i>
                    Akses Cepat
                </h3>
            </div>
            <div class="card-body p-0">
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a href="{{ route('admin.bookings.index') }}" class="nav-link py-3 border-bottom text-dark">
                            <i class="fas fa-calendar-alt mr-2 text-primary"></i>
                            Kelola Semua Booking
                            <span class="badge bg-primary float-right mt-1">OPEN</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.courts.index') }}" class="nav-link py-3 border-bottom text-dark">
                            <i class="fas fa-plus-circle mr-2 text-success"></i>
                            Tambah Lapangan Baru
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.landing.edit') }}" class="nav-link py-3 text-dark">
                            <i class="fas fa-edit mr-2 text-info"></i>
                            Edit Konten Landing
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card card-outline card-dark shadow-sm border-0 overflow-hidden">
            <div class="card-header border-0 mt-2">
                <h3 class="card-title font-weight-bold">Booking Terbaru</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-tool btn-sm font-weight-bold text-primary">
                        LIHAT SEMUA DATA <i class="fas fa-external-link-alt ml-1"></i>
                    </a>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover table-valign-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-top-0">Pelanggan</th>
                            <th class="border-top-0">Lapangan</th>
                            <th class="border-top-0">Jadwal</th>
                            <th class="border-top-0 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($latestBookings as $booking)
                        <tr>
                            <td class="font-weight-bold text-dark">{{ $booking->user->name }}</td>
                            <td class="text-muted">{{ $booking->court->name }}</td>
                            <td>
                                <span class="d-block font-weight-bold">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</span>
                                <small class="text-muted">{{ substr($booking->start_time, 0, 5) }} WIB</small>
                            </td>
                            <td class="text-center">
                                @if($booking->status == 'pending')
                                    <span class="badge badge-warning text-uppercase shadow-sm py-2 px-3">Pending</span>
                                @elseif($booking->status == 'approved')
                                    <span class="badge badge-success text-uppercase shadow-sm py-2 px-3">Approved</span>
                                @else
                                    <span class="badge badge-danger text-uppercase shadow-sm py-2 px-3">{{ $booking->status }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-5 text-center text-muted">
                                <i class="fas fa-folder-open fa-2x mb-2 d-block opacity-25"></i>
                                Belum ada data booking.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @php
            $defaultData = [0, 0, 0, 0, 0, 0, 0];
            $defaultDays = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
        @endphp
        const bookingRevenue = @json($weeklyBookingRevenue ?? $defaultData);
        const membershipRevenue = @json($weeklyMembershipRevenue ?? $defaultData);
        const days = @json($weeklyBookingDays ?? $defaultDays);

        var options = {
            series: [{
                name: 'Pendapatan Lapangan',
                data: bookingRevenue
            }, {
                name: 'Pendapatan Membership',
                data: membershipRevenue
            }],
            chart: {
                type: 'area',
                height: 350,
                toolbar: { show: false },
                fontFamily: '"Source Sans Pro", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol"'
            },
            dataLabels: { enabled: false },
            colors: ['#28a745', '#6f42c1'],
            stroke: {
                curve: 'smooth',
                width: 3
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.45,
                    opacityTo: 0.05,
                    stops: [20, 100, 100, 100]
                }
            },
            xaxis: {
                categories: days,
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                labels: {
                    formatter: function(val) { 
                        return 'Rp ' + val.toLocaleString('id-ID');
                    }
                }
            },
            grid: {
                borderColor: '#f1f1f1',
                strokeDashArray: 4,
            },
            markers: {
                size: 4,
                strokeWidth: 2,
                colors: ['#28a745', '#6f42c1'],
                hover: { size: 7 }
            },
            tooltip: {
                theme: 'light',
                y: {
                    formatter: function(val) {
                        return 'Rp ' + val.toLocaleString('id-ID');
                    }
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#booking-chart-lte"), options);
        chart.render();
    });
</script>
@endpush
