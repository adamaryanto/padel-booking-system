@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('header', 'Dashboard Overview')

@section('content')
<div class="mb-4 mt-n2">
    <p class="text-muted text-sm">Selamat datang kembali, Admin.</p>
</div>

<!-- Statistics Cards -->
<div class="row">
    <!-- Card 1: Total Booking -->
    <div class="col-6 col-sm-6 col-lg-3 mb-4">
        <div class="card h-100 p-4 border-gray-200 stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted font-weight-bold text-xs uppercase tracking-wider">Total Booking</span>
                <span class="badge badge-success">+12%</span>
            </div>
            <h3 class="font-weight-extrabold text-dark tracking-tight mb-1 stat-value" style="font-size: 2.25rem;">
                {{ $totalBookings }}
            </h3>
            <p class="text-muted text-xs mb-0">dari bulan lalu</p>
        </div>
    </div>

    <!-- Card 2: Revenue -->
    <div class="col-6 col-sm-6 col-lg-3 mb-4">
        <div class="card h-100 p-4 border-gray-200 stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted font-weight-bold text-xs uppercase tracking-wider">Revenue</span>
                <span class="badge badge-success">+18%</span>
            </div>
            <h3 class="font-weight-extrabold text-dark tracking-tight mb-1 revenue-value" style="font-size: 1.75rem; line-height: 2.25rem;">
                Rp {{ number_format($totalRevenue, 0, ',', '.') }}
            </h3>
            <p class="text-muted text-xs mb-0">dari bulan lalu</p>
        </div>
    </div>

    <!-- Card 3: Active Members -->
    <div class="col-6 col-sm-6 col-lg-3 mb-4">
        <div class="card h-100 p-4 border-gray-200 stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted font-weight-bold text-xs uppercase tracking-wider">Active Members</span>
                <span class="badge badge-success">+8%</span>
            </div>
            <h3 class="font-weight-extrabold text-dark tracking-tight mb-1 stat-value" style="font-size: 2.25rem;">
                {{ \App\Models\Membership::where('status', 'active')->count() }}
            </h3>
            <p class="text-muted text-xs mb-0">dari bulan lalu</p>
        </div>
    </div>

    <!-- Card 4: Total Courts -->
    <div class="col-6 col-sm-6 col-lg-3 mb-4">
        <div class="card h-100 p-4 border-gray-200 stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted font-weight-bold text-xs uppercase tracking-wider">Total Courts</span>
                <span class="badge badge-info" style="background-color: rgba(16, 185, 129, 0.1) !important; color: #10b981 !important;">Active</span>
            </div>
            <h3 class="font-weight-extrabold text-dark tracking-tight mb-1 stat-value" style="font-size: 2.25rem;">
                {{ $totalCourts }}
            </h3>
            <p class="text-muted text-xs mb-0">2 Lapangan Baru</p>
        </div>
    </div>
</div>

<div class="row">
    <!-- Revenue Analytics Chart -->
    <div class="col-lg-8 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2 border-0 bg-transparent">
                <h3 class="card-title font-weight-bold text-dark mb-0">Revenue Analytics</h3>
                <!-- Filter Buttons -->
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-outline-primary active">Mingguan</button>
                    <button type="button" class="btn btn-outline-primary">Bulanan</button>
                    <button type="button" class="btn btn-outline-primary">Tahunan</button>
                </div>
            </div>
            <div class="card-body">
                <div id="revenue-chart" style="height: 300px;"></div>
            </div>
        </div>
    </div>
    
    <!-- Booking Status Donut Chart -->
    @php
        $pendingCount = \App\Models\Booking::where('status', 'pending')->count();
        $approvedCount = \App\Models\Booking::where('status', 'approved')->count();
        $completedCount = \App\Models\Booking::where('status', 'completed')->count();
        $cancelledCount = \App\Models\Booking::where('status', 'cancelled')->count();
        $totalStatus = $pendingCount + $approvedCount + $completedCount + $cancelledCount;
    @endphp
    <div class="col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-header border-0 bg-transparent">
                <h3 class="card-title font-weight-bold text-dark mb-0">Booking Status</h3>
            </div>
            <div class="card-body d-flex flex-column justify-content-between">
                <div id="status-donut-chart" style="height: 250px;"></div>
                <div class="d-flex justify-content-around text-center text-xs mt-3">
                    <div>
                        <span class="d-block text-muted">Pending</span>
                        <span class="font-weight-bold text-dark">{{ $pendingCount }}</span>
                    </div>
                    <div>
                        <span class="d-block text-muted">Confirmed</span>
                        <span class="font-weight-bold text-dark">{{ $approvedCount }}</span>
                    </div>
                    <div>
                        <span class="d-block text-muted">Completed</span>
                        <span class="font-weight-bold text-dark">{{ $completedCount }}</span>
                    </div>
                    <div>
                        <span class="d-block text-muted">Cancelled</span>
                        <span class="font-weight-bold text-dark">{{ $cancelledCount }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Bookings Table -->
    <div class="col-12 mb-4">
        <div class="card border-0">
            <div class="card-header border-0 bg-transparent">
                <h3 class="card-title font-weight-bold text-dark mb-0">Recent Bookings</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover align-items-center mb-0 d-none d-md-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Court</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($latestBookings as $booking)
                        <tr>
                            <td class="font-weight-bold text-dark">{{ $booking->user->name }}</td>
                            <td class="text-muted">{{ $booking->court->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</td>
                            <td>{{ substr($booking->start_time, 0, 5) }} WIB</td>
                            <td>
                                @if($booking->status == 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @elseif($booking->status == 'approved' || $booking->status == 'confirmed')
                                    <span class="badge badge-success">Confirmed</span>
                                @elseif($booking->status == 'completed')
                                    <span class="badge badge-info">Completed</span>
                                @else
                                    <span class="badge badge-danger">{{ $booking->status }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.bookings.index') }}?booking_id={{ $booking->id }}" class="btn btn-outline-primary btn-xs mr-2">Detail</a>
                                <a href="{{ route('admin.bookings.index') }}?booking_id={{ $booking->id }}&edit=1" class="btn btn-outline-primary btn-xs">Edit</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-5 text-center text-muted">
                                <i class="fas fa-calendar-times fa-2x mb-2 d-block opacity-25"></i>
                                Belum ada data booking.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Mobile View Card List -->
                <div class="d-block d-md-none px-3 pt-2 pb-3">
                    @forelse($latestBookings as $booking)
                    <div class="p-3 mb-3 border border-gray-200 rounded-lg bg-light shadow-sm">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h5 class="font-weight-bold text-dark text-sm mb-1" style="font-size: 0.95rem;">{{ $booking->user->name }}</h5>
                                <span class="text-muted text-xs font-weight-bold"><i class="fas fa-table-tennis-paddle-ball mr-1 text-success"></i>{{ $booking->court->name }}</span>
                            </div>
                            <div>
                                @if($booking->status == 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @elseif($booking->status == 'approved' || $booking->status == 'confirmed')
                                    <span class="badge badge-success">Confirmed</span>
                                @elseif($booking->status == 'completed')
                                    <span class="badge badge-info">Completed</span>
                                @else
                                    <span class="badge badge-danger">{{ $booking->status }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="border-top border-bottom py-2 my-2 d-flex justify-content-between text-xs text-muted">
                            <div>
                                <i class="far fa-calendar-alt mr-1"></i> {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}
                            </div>
                            <div>
                                <i class="far fa-clock mr-1"></i> {{ substr($booking->start_time, 0, 5) }} WIB
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.bookings.index') }}?booking_id={{ $booking->id }}" class="btn btn-outline-primary btn-xs mr-2">Detail</a>
                            <a href="{{ route('admin.bookings.index') }}?booking_id={{ $booking->id }}&edit=1" class="btn btn-outline-primary btn-xs">Edit</a>
                        </div>
                    </div>
                    @empty
                    <div class="py-5 text-center text-muted">
                        <i class="fas fa-calendar-times fa-2x mb-2 d-block opacity-25"></i>
                        Belum ada data booking.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Revenue Chart
        const weeklyBookingDays = @json($weeklyBookingDays);
        const weeklyBookingRevenue = @json($weeklyBookingRevenue);
        const weeklyMembershipRevenue = @json($weeklyMembershipRevenue);

        const revenueOptions = {
            series: [{
                name: 'Booking Lapangan',
                data: weeklyBookingRevenue
            }, {
                name: 'Membership',
                data: weeklyMembershipRevenue
            }],
            chart: {
                type: 'area',
                height: 300,
                toolbar: { show: false },
                fontFamily: '"Inter", sans-serif'
            },
            dataLabels: { enabled: false },
            colors: ['#10b981', '#059669'], // Emerald Theme Accent
            stroke: {
                curve: 'smooth',
                width: 2.5
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.3,
                    opacityTo: 0.02,
                    stops: [0, 90, 100]
                }
            },
            xaxis: {
                categories: weeklyBookingDays,
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
                borderColor: '#f1f5f9',
                strokeDashArray: 4,
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

        const revenueChart = new ApexCharts(document.querySelector("#revenue-chart"), revenueOptions);
        revenueChart.render();

        // Status Donut Chart
        const statusOptions = {
            series: [{{ $pendingCount }}, {{ $approvedCount }}, {{ $completedCount }}, {{ $cancelledCount }}],
            labels: ['Pending', 'Confirmed', 'Completed', 'Cancelled'],
            chart: {
                type: 'donut',
                height: 250,
                fontFamily: '"Inter", sans-serif'
            },
            colors: ['#f59e0b', '#10b981', '#3b82f6', '#ef4444'],
            stroke: { show: false },
            legend: { show: false },
            dataLabels: { enabled: false },
            plotOptions: {
                pie: {
                    donut: {
                        size: '75%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Total',
                                formatter: function (w) {
                                    return w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                                }
                            }
                        }
                    }
                }
            }
        };

        const statusChart = new ApexCharts(document.querySelector("#status-donut-chart"), statusOptions);
        statusChart.render();
    });
</script>
@endpush

@push('styles')
<style>
    @media (max-width: 576px) {
        .stat-card {
            padding: 1.25rem !important;
        }
        .stat-value {
            font-size: 1.75rem !important;
        }
        .revenue-value {
            font-size: 1.35rem !important;
            line-height: 1.85rem !important;
        }
        .card-body {
            padding: 1rem !important;
        }
    }
</style>
@endpush
