@extends('layouts.admin')

@section('title', 'Manajemen Booking')
@section('header', 'Manajemen Booking')

@section('content')
<div class="mb-4 mt-n2">
    <p class="text-muted text-sm">Kelola dan pantau seluruh transaksi booking lapangan dan langganan membership pemain.</p>
</div>

<!-- Tab Navigation -->
<div class="d-flex border-bottom mb-4">
    <a href="?tab=bookings" class="px-4 py-2 font-weight-bold text-sm border-bottom-2 transition {{ request('tab', 'bookings') == 'bookings' ? 'text-emerald border-emerald' : 'text-muted border-transparent' }}" style="{{ request('tab', 'bookings') == 'bookings' ? 'color: #10b981; border-color: #10b981; border-bottom: 2px solid #10b981;' : 'border-bottom: 2px solid transparent;' }}">
        Booking Lapangan
    </a>
    <a href="?tab=memberships" class="px-4 py-2 font-weight-bold text-sm border-bottom-2 transition {{ request('tab') == 'memberships' ? 'text-emerald border-emerald' : 'text-muted border-transparent' }}" style="{{ request('tab') == 'memberships' ? 'color: #10b981; border-color: #10b981; border-bottom: 2px solid #10b981;' : 'border-bottom: 2px solid transparent;' }}">
        Booking Membership
    </a>
</div>

@if(request('tab', 'bookings') == 'bookings')
    <!-- COURT BOOKINGS TAB -->
    
    <!-- Filters Bar -->
    <div class="card p-3 border-gray-200 mb-4 bg-white">
        <div class="row align-items-center">
            <!-- Search User -->
            <div class="col-md-3 mb-2 mb-md-0">
                <label class="text-xs font-weight-bold text-muted uppercase tracking-wider mb-1">Search User</label>
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-transparent border-right-0 border-gray-200"><i class="fas fa-search text-muted"></i></span>
                    </div>
                    <input type="text" id="filter-user" class="form-control border-left-0 border-gray-200" placeholder="Nama user...">
                </div>
            </div>
            
            <!-- Date Filter -->
            <div class="col-md-3 mb-2 mb-md-0">
                <label class="text-xs font-weight-bold text-muted uppercase tracking-wider mb-1">Date</label>
                <input type="date" id="filter-date" class="form-control form-control-sm border-gray-200">
            </div>

            <!-- Court Filter -->
            <div class="col-md-3 mb-2 mb-md-0">
                <label class="text-xs font-weight-bold text-muted uppercase tracking-wider mb-1">Court</label>
                <select id="filter-court" class="form-control form-control-sm border-gray-200 custom-select">
                    <option value="all">Semua Lapangan</option>
                    @foreach($bookings->pluck('court.name')->unique() as $cName)
                        <option value="{{ strtolower($cName) }}">{{ $cName }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter -->
            <div class="col-md-3">
                <label class="text-xs font-weight-bold text-muted uppercase tracking-wider mb-1">Booking Status</label>
                <select id="filter-status" class="form-control form-control-sm border-gray-200 custom-select">
                    <option value="all">Semua Status</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Confirmed</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Bookings Table Card -->
    <div class="card border-gray-200 bg-white overflow-hidden shadow-none mb-4" style="border-radius: 1.5rem !important;">
        <div class="card-body p-0 table-responsive">
            <table class="table table-hover mb-0" id="bookings-table">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>User</th>
                        <th>Court</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr class="booking-row" 
                            data-user="{{ strtolower($booking->user->name) }}"
                            data-date="{{ $booking->booking_date }}"
                            data-court="{{ strtolower($booking->court->name) }}"
                            data-status="{{ $booking->status }}">
                            <td class="font-weight-bold text-dark">#BKG-{{ $booking->id }}</td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="font-weight-bold text-dark">{{ $booking->user->name }}</span>
                                    <span class="text-muted text-xs">{{ $booking->user->email }}</span>
                                </div>
                            </td>
                            <td>{{ $booking->court->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</td>
                            <td>{{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }} WIB</td>
                            <td>
                                @if($booking->payment)
                                    <div class="d-flex flex-column align-items-start">
                                        @php
                                            $badgeClass = 'badge-warning';
                                            if ($booking->payment->status == 'verified') $badgeClass = 'badge-success';
                                            if ($booking->payment->status == 'rejected') $badgeClass = 'badge-danger';
                                        @endphp
                                        <span class="badge {{ $badgeClass }} mb-1">
                                            {{ $booking->payment->status }}
                                        </span>
                                        @if($booking->payment->proof_of_payment)
                                            <a href="{{ asset('storage/' . $booking->payment->proof_of_payment) }}" target="_blank" class="text-xs font-weight-bold text-primary">
                                                <i class="fas fa-image mr-1"></i> Bukti Transfer
                                            </a>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-muted text-xs italic">Belum Ada</span>
                                @endif
                            </td>
                            <td>
                                @if($booking->status == 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @elseif($booking->status == 'approved' || $booking->status == 'confirmed')
                                    <span class="badge badge-success">Confirmed</span>
                                @elseif($booking->status == 'completed')
                                    <span class="badge badge-info">Completed</span>
                                @elseif($booking->status == 'cancelled')
                                    <span class="badge badge-danger">Cancelled</span>
                                @else
                                    <span class="badge badge-secondary">{{ $booking->status }}</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <button class="btn btn-outline-primary btn-xs view-booking-detail"
                                        data-id="{{ $booking->id }}"
                                        data-user-name="{{ $booking->user->name }}"
                                        data-user-email="{{ $booking->user->email }}"
                                        data-user-phone="{{ $booking->user->phone ?? '-' }}"
                                        data-court-name="{{ $booking->court->name }}"
                                        data-date="{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}"
                                        data-time="{{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }} WIB"
                                        data-price="Rp {{ number_format($booking->total_price, 0, ',', '.') }}"
                                        data-status="{{ $booking->status }}"
                                        data-payment-exists="{{ $booking->payment ? '1' : '0' }}"
                                        data-payment-status="{{ $booking->payment->status ?? '-' }}"
                                        data-payment-amount="{{ $booking->payment ? 'Rp ' . number_format($booking->payment->gross_amount, 0, ',', '.') : '-' }}"
                                        data-payment-type="{{ $booking->payment->payment_type ?? 'Manual Bank Transfer' }}"
                                        data-payment-proof="{{ $booking->payment && $booking->payment->proof_of_payment ? asset('storage/' . $booking->payment->proof_of_payment) : '' }}"
                                        data-approve-url="{{ route('admin.bookings.approve', $booking) }}"
                                        data-complete-url="{{ route('admin.bookings.complete', $booking) }}"
                                        data-cancel-url="{{ route('admin.bookings.cancel', $booking) }}"
                                        data-payment-verify-url="{{ $booking->payment ? route('payments.verify', $booking->payment) : '' }}"
                                        data-payment-reject-url="{{ $booking->payment ? route('payments.reject', $booking->payment) : '' }}">
                                    View Detail
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-5 text-center text-muted">
                                <i class="fas fa-calendar-times fa-3x mb-3 d-block opacity-25"></i>
                                Belum ada data booking.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@else
    <!-- MEMBERSHIP SUBSCRIPTIONS TAB -->
    
    <!-- Filters Bar (Membership) -->
    <div class="card p-3 border-gray-200 mb-4 bg-white">
        <div class="row align-items-center">
            <!-- Search User -->
            <div class="col-md-6 mb-2 mb-md-0">
                <label class="text-xs font-weight-bold text-muted uppercase tracking-wider mb-1">Search User</label>
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-transparent border-right-0 border-gray-200"><i class="fas fa-search text-muted"></i></span>
                    </div>
                    <input type="text" id="filter-member-user" class="form-control border-left-0 border-gray-200" placeholder="Nama user...">
                </div>
            </div>

            <!-- Status Filter -->
            <div class="col-md-6">
                <label class="text-xs font-weight-bold text-muted uppercase tracking-wider mb-1">Subscription Status</label>
                <select id="filter-member-status" class="form-control form-control-sm border-gray-200 custom-select">
                    <option value="all">Semua Status</option>
                    <option value="active">Active</option>
                    <option value="pending">Pending</option>
                    <option value="expired">Expired</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Memberships Table Card -->
    <div class="card border-gray-200 bg-white overflow-hidden shadow-none mb-4" style="border-radius: 1.5rem !important;">
        <div class="card-body p-0 table-responsive">
            <table class="table table-hover mb-0" id="memberships-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Package</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($memberships as $membership)
                        <tr class="membership-row"
                            data-user="{{ strtolower($membership->user->name) }}"
                            data-status="{{ $membership->status }}">
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="font-weight-bold text-dark">{{ $membership->user->name }}</span>
                                    <span class="text-muted text-xs">{{ $membership->user->email }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-purple px-2.5 py-1 text-white text-xs font-weight-bold" style="background-color: #6f42c1;">
                                    {{ $membership->tier->name }}
                                </span>
                            </td>
                            <td>{{ $membership->start_date ? $membership->start_date->format('d M Y') : '-' }}</td>
                            <td>{{ $membership->end_date ? $membership->end_date->format('d M Y') : '-' }}</td>
                            <td>
                                @if($membership->payment)
                                    <div class="d-flex flex-column align-items-start">
                                        @php
                                            $mBadgeClass = 'badge-warning';
                                            if ($membership->payment->status == 'verified') $mBadgeClass = 'badge-success';
                                            if ($membership->payment->status == 'rejected') $mBadgeClass = 'badge-danger';
                                        @endphp
                                        <span class="badge {{ $mBadgeClass }} mb-1">
                                            {{ $membership->payment->status }}
                                        </span>
                                        @if($membership->payment->proof_of_payment)
                                            <a href="{{ asset('storage/' . $membership->payment->proof_of_payment) }}" target="_blank" class="text-xs font-weight-bold text-primary">
                                                <i class="fas fa-image mr-1"></i> Bukti Transfer
                                            </a>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-muted text-xs italic">Belum Ada</span>
                                @endif
                            </td>
                            <td>
                                @if($membership->status == 'active')
                                    <span class="badge badge-success">Active</span>
                                @elseif($membership->status == 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @elseif($membership->status == 'expired')
                                    <span class="badge badge-secondary">Expired</span>
                                @elseif($membership->status == 'rejected')
                                    <span class="badge badge-danger">Rejected</span>
                                @else
                                    <span class="badge badge-secondary">{{ $membership->status }}</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <button class="btn btn-outline-primary btn-xs view-membership-detail"
                                        data-id="{{ $membership->id }}"
                                        data-user-name="{{ $membership->user->name }}"
                                        data-user-email="{{ $membership->user->email }}"
                                        data-user-phone="{{ $membership->user->phone ?? '-' }}"
                                        data-package-name="{{ $membership->tier->name }}"
                                        data-package-price="Rp {{ number_format($membership->tier->price, 0, ',', '.') }}"
                                        data-start="{{ $membership->start_date ? $membership->start_date->format('d M Y') : '-' }}"
                                        data-end="{{ $membership->end_date ? $membership->end_date->format('d M Y') : '-' }}"
                                        data-status="{{ $membership->status }}"
                                        data-payment-exists="{{ $membership->payment ? '1' : '0' }}"
                                        data-payment-status="{{ $membership->payment->status ?? '-' }}"
                                        data-payment-amount="{{ $membership->payment ? 'Rp ' . number_format($membership->payment->gross_amount, 0, ',', '.') : '-' }}"
                                        data-payment-type="{{ $membership->payment->payment_type ?? 'Manual Bank Transfer' }}"
                                        data-payment-proof="{{ $membership->payment && $membership->payment->proof_of_payment ? asset('storage/' . $membership->payment->proof_of_payment) : '' }}"
                                        data-payment-verify-url="{{ $membership->payment ? route('payments.verify', $membership->payment) : '' }}"
                                        data-payment-reject-url="{{ $membership->payment ? route('payments.reject', $membership->payment) : '' }}">
                                    Detail
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-5 text-center text-muted">
                                <i class="fas fa-id-card fa-3x mb-3 d-block opacity-25"></i>
                                Belum ada data subscription membership.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endif

<!-- Court Booking Detail Modal -->
<div class="modal fade" id="bookingDetailModal" tabindex="-1" role="dialog" aria-labelledby="bookingDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border-0 rounded-2xl bg-white shadow-lg overflow-hidden">
            <div class="modal-header border-0 bg-light p-4">
                <h5 class="modal-title font-weight-bold text-dark" id="bookingDetailModalLabel">Detail Booking Lapangan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <!-- Booking Timeline -->
                <div class="mb-5">
                    <h6 class="font-weight-bold text-muted uppercase tracking-wider text-xs mb-3">Booking Timeline</h6>
                    <div class="d-flex justify-content-between text-center timeline-steps">
                        <div class="timeline-step flex-grow-1 position-relative active">
                            <div class="step-circle mx-auto bg-emerald text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; background-color: #10b981;">1</div>
                            <span class="text-xs font-weight-bold d-block mt-2">Booked</span>
                        </div>
                        <div class="timeline-step flex-grow-1 position-relative" id="step-paid">
                            <div class="step-circle mx-auto bg-gray-200 text-muted rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">2</div>
                            <span class="text-xs font-weight-bold d-block mt-2">Paid</span>
                        </div>
                        <div class="timeline-step flex-grow-1 position-relative" id="step-confirmed">
                            <div class="step-circle mx-auto bg-gray-200 text-muted rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">3</div>
                            <span class="text-xs font-weight-bold d-block mt-2">Confirmed</span>
                        </div>
                        <div class="timeline-step flex-grow-1 position-relative" id="step-completed">
                            <div class="step-circle mx-auto bg-gray-200 text-muted rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">4</div>
                            <span class="text-xs font-weight-bold d-block mt-2">Completed</span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- User & Booking Info -->
                    <div class="col-md-6 mb-4 mb-md-0 border-right pr-4">
                        <h6 class="font-weight-bold text-muted uppercase tracking-wider text-xs mb-3">User Information</h6>
                        <div class="mb-4">
                            <span class="text-xs text-muted d-block">Full Name</span>
                            <span class="font-weight-bold text-dark d-block" id="modal-user-name">-</span>
                        </div>
                        <div class="mb-4">
                            <span class="text-xs text-muted d-block">Email Address</span>
                            <span class="font-weight-bold text-dark d-block" id="modal-user-email">-</span>
                        </div>
                        <div class="mb-4">
                            <span class="text-xs text-muted d-block">Phone Number</span>
                            <span class="font-weight-bold text-dark d-block" id="modal-user-phone">-</span>
                        </div>

                        <hr class="my-4">

                        <h6 class="font-weight-bold text-muted uppercase tracking-wider text-xs mb-3">Booking Information</h6>
                        <div class="mb-3">
                            <span class="text-xs text-muted d-block">Court</span>
                            <span class="font-weight-bold text-dark d-block" id="modal-court-name">-</span>
                        </div>
                        <div class="mb-3">
                            <span class="text-xs text-muted d-block">Date & Time</span>
                            <span class="font-weight-bold text-dark d-block" id="modal-booking-time">-</span>
                        </div>
                        <div class="mb-3">
                            <span class="text-xs text-muted d-block">Total Payment</span>
                            <span class="font-weight-bold text-emerald text-md d-block" id="modal-booking-price" style="color: #10b981;">-</span>
                        </div>
                    </div>

                    <!-- Payment Info & Actions -->
                    <div class="col-md-6 pl-4">
                        <h6 class="font-weight-bold text-muted uppercase tracking-wider text-xs mb-3">Payment Information</h6>
                        <div class="mb-3">
                            <span class="text-xs text-muted d-block">Payment Status</span>
                            <span class="badge text-uppercase" id="modal-payment-status">-</span>
                        </div>
                        <div class="mb-3">
                            <span class="text-xs text-muted d-block">Amount Charged</span>
                            <span class="font-weight-bold text-dark d-block" id="modal-payment-amount">-</span>
                        </div>
                        <div class="mb-3">
                            <span class="text-xs text-muted d-block">Payment Method</span>
                            <span class="font-weight-bold text-dark d-block" id="modal-payment-type">-</span>
                        </div>
                        <div class="mb-3 d-none" id="modal-payment-proof-container">
                            <span class="text-xs text-muted d-block mb-1">Proof of Payment</span>
                            <a href="#" id="modal-payment-proof-link" target="_blank">
                                <img src="" id="modal-payment-proof-img" class="img-thumbnail rounded w-100" style="max-height: 180px; object-fit: cover;">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer bg-light border-0 p-4 d-flex justify-content-between flex-wrap">
                <!-- Left: Reject Payment Form -->
                <div>
                    <form id="payment-reject-form" method="POST" action="" class="d-none">
                        @csrf
                    </form>
                    <button type="button" class="btn btn-outline-danger text-xs py-2 mr-2 d-none" id="btn-reject-payment">
                        Reject Payment
                    </button>
                    
                    <form id="booking-cancel-form" method="POST" action="" class="d-none">
                        @csrf
                    </form>
                    <button type="button" class="btn btn-outline-danger text-xs py-2" id="btn-cancel-booking">
                        Cancel Booking
                    </button>
                </div>

                <!-- Right: Approve Payment & Confirm Booking Forms -->
                <div>
                    <form id="payment-verify-form" method="POST" action="" class="d-none">
                        @csrf
                    </form>
                    <button type="button" class="btn btn-success text-xs py-2 mr-2 d-none" id="btn-verify-payment">
                        Verify Payment
                    </button>

                    <form id="booking-approve-form" method="POST" action="" class="d-none">
                        @csrf
                    </form>
                    <button type="button" class="btn btn-primary text-xs py-2 mr-2 d-none" id="btn-approve-booking">
                        Confirm Booking
                    </button>

                    <form id="booking-complete-form" method="POST" action="" class="d-none">
                        @csrf
                    </form>
                    <button type="button" class="btn btn-info text-xs py-2 text-white d-none" id="btn-complete-booking" style="background-color: #3b82f6 !important; border-color: #3b82f6 !important;">
                        Complete Booking
                    </button>
                    
                    <button type="button" class="btn btn-outline-primary text-xs py-2" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Membership Subscription Detail Modal -->
<div class="modal fade" id="membershipDetailModal" tabindex="-1" role="dialog" aria-labelledby="membershipDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border-0 rounded-2xl bg-white shadow-lg overflow-hidden">
            <div class="modal-header border-0 bg-light p-4">
                <h5 class="modal-title font-weight-bold text-dark" id="membershipDetailModalLabel">Detail Subscription Membership</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div class="row">
                    <!-- User & Subscription -->
                    <div class="col-md-6 mb-4 mb-md-0 border-right pr-4">
                        <h6 class="font-weight-bold text-muted uppercase tracking-wider text-xs mb-3">User Information</h6>
                        <div class="mb-4">
                            <span class="text-xs text-muted d-block">Full Name</span>
                            <span class="font-weight-bold text-dark d-block" id="modal-member-user-name">-</span>
                        </div>
                        <div class="mb-4">
                            <span class="text-xs text-muted d-block">Email Address</span>
                            <span class="font-weight-bold text-dark d-block" id="modal-member-user-email">-</span>
                        </div>
                        
                        <hr class="my-4">

                        <h6 class="font-weight-bold text-muted uppercase tracking-wider text-xs mb-3">Membership Details</h6>
                        <div class="mb-3">
                            <span class="text-xs text-muted d-block">Package Tier</span>
                            <span class="badge bg-purple text-white px-2 py-1 text-xs font-weight-bold d-inline-block" id="modal-member-package">-</span>
                        </div>
                        <div class="mb-3">
                            <span class="text-xs text-muted d-block">Start Date</span>
                            <span class="font-weight-bold text-dark d-block" id="modal-member-start">-</span>
                        </div>
                        <div class="mb-3">
                            <span class="text-xs text-muted d-block">End Date</span>
                            <span class="font-weight-bold text-dark d-block" id="modal-member-end">-</span>
                        </div>
                        <div class="mb-3">
                            <span class="text-xs text-muted d-block">Status</span>
                            <span class="badge text-uppercase" id="modal-member-status">-</span>
                        </div>
                    </div>

                    <!-- Payment Info -->
                    <div class="col-md-6 pl-4">
                        <h6 class="font-weight-bold text-muted uppercase tracking-wider text-xs mb-3">Payment Information</h6>
                        <div class="mb-3">
                            <span class="text-xs text-muted d-block">Payment Status</span>
                            <span class="badge text-uppercase" id="modal-member-payment-status">-</span>
                        </div>
                        <div class="mb-3">
                            <span class="text-xs text-muted d-block">Amount Charged</span>
                            <span class="font-weight-bold text-dark d-block" id="modal-member-payment-amount">-</span>
                        </div>
                        <div class="mb-3">
                            <span class="text-xs text-muted d-block">Payment Method</span>
                            <span class="font-weight-bold text-dark d-block" id="modal-member-payment-type">-</span>
                        </div>
                        <div class="mb-3 d-none" id="modal-member-payment-proof-container">
                            <span class="text-xs text-muted d-block mb-1">Proof of Payment</span>
                            <a href="#" id="modal-member-payment-proof-link" target="_blank">
                                <img src="" id="modal-member-payment-proof-img" class="img-thumbnail rounded w-100" style="max-height: 180px; object-fit: cover;">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer bg-light border-0 p-4 d-flex justify-content-between flex-wrap">
                <!-- Left: Reject Payment Form -->
                <div>
                    <form id="member-payment-reject-form" method="POST" action="" class="d-none">
                        @csrf
                    </form>
                    <button type="button" class="btn btn-outline-danger text-xs py-2 mr-2 d-none" id="btn-member-reject-payment">
                        Reject Payment
                    </button>
                </div>

                <!-- Right: Approve Payment & Close -->
                <div>
                    <form id="member-payment-verify-form" method="POST" action="" class="d-none">
                        @csrf
                    </form>
                    <button type="button" class="btn btn-success text-xs py-2 mr-2 d-none" id="btn-member-verify-payment">
                        Verify Payment & Activate
                    </button>
                    
                    <button type="button" class="btn btn-outline-primary text-xs py-2" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .transition {
        transition: all 0.2s ease-in-out;
    }
    .text-emerald {
        color: #10b981 !important;
    }
    .border-emerald {
        border-color: #10b981 !important;
    }
    .timeline-steps {
        max-width: 600px;
        margin: 0 auto;
    }
    .timeline-step:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 14px;
        left: calc(50% + 14px);
        right: calc(-50% + 14px);
        height: 2px;
        background-color: #e5e7eb;
        z-index: 1;
    }
    .timeline-step.active:not(:last-child)::after {
        background-color: #10b981;
    }
    .timeline-step .step-circle {
        position: relative;
        z-index: 2;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- CLIENT-SIDE FILTERS FOR COURT BOOKINGS ---
        const userFilterInput = document.getElementById('filter-user');
        const dateFilterInput = document.getElementById('filter-date');
        const courtFilterSelect = document.getElementById('filter-court');
        const statusFilterSelect = document.getElementById('filter-status');
        const bookingRows = document.querySelectorAll('.booking-row');

        function filterBookings() {
            const userQuery = userFilterInput ? userFilterInput.value.toLowerCase() : '';
            const dateQuery = dateFilterInput ? dateFilterInput.value : '';
            const courtQuery = courtFilterSelect ? courtFilterSelect.value : 'all';
            const statusQuery = statusFilterSelect ? statusFilterSelect.value : 'all';

            bookingRows.forEach(row => {
                const user = row.getAttribute('data-user');
                const date = row.getAttribute('data-date');
                const court = row.getAttribute('data-court');
                const status = row.getAttribute('data-status');

                const matchesUser = user.includes(userQuery);
                const matchesDate = !dateQuery || date === dateQuery;
                const matchesCourt = courtQuery === 'all' || court === courtQuery;
                const matchesStatus = statusQuery === 'all' || status === statusQuery || (statusQuery === 'approved' && status === 'confirmed') || (statusQuery === 'confirmed' && status === 'approved');

                if (matchesUser && matchesDate && matchesCourt && matchesStatus) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        if (userFilterInput) userFilterInput.addEventListener('input', filterBookings);
        if (dateFilterInput) dateFilterInput.addEventListener('change', filterBookings);
        if (courtFilterSelect) courtFilterSelect.addEventListener('change', filterBookings);
        if (statusFilterSelect) statusFilterSelect.addEventListener('change', filterBookings);

        // --- CLIENT-SIDE FILTERS FOR MEMBERSHIPS ---
        const memberUserFilterInput = document.getElementById('filter-member-user');
        const memberStatusFilterSelect = document.getElementById('filter-member-status');
        const membershipRows = document.querySelectorAll('.membership-row');

        function filterMemberships() {
            const userQuery = memberUserFilterInput ? memberUserFilterInput.value.toLowerCase() : '';
            const statusQuery = memberStatusFilterSelect ? memberStatusFilterSelect.value : 'all';

            membershipRows.forEach(row => {
                const user = row.getAttribute('data-user');
                const status = row.getAttribute('data-status');

                const matchesUser = user.includes(userQuery);
                const matchesStatus = statusQuery === 'all' || status === statusQuery;

                if (matchesUser && matchesStatus) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        if (memberUserFilterInput) memberUserFilterInput.addEventListener('input', filterMemberships);
        if (memberStatusFilterSelect) memberStatusFilterSelect.addEventListener('change', filterMemberships);

        // --- COURT BOOKINGS DETAIL MODAL POPULATE ---
        $('.view-booking-detail').on('click', function() {
            const id = $(this).data('id');
            const userName = $(this).data('user-name');
            const userEmail = $(this).data('user-email');
            const userPhone = $(this).data('user-phone');
            const courtName = $(this).data('court-name');
            const date = $(this).data('date');
            const time = $(this).data('time');
            const price = $(this).data('price');
            const status = $(this).data('status');
            
            const paymentExists = $(this).data('payment-exists');
            const paymentStatus = $(this).data('payment-status');
            const paymentAmount = $(this).data('payment-amount');
            const paymentType = $(this).data('payment-type');
            const paymentProof = $(this).data('payment-proof');
            
            const approveUrl = $(this).data('approve-url');
            const completeUrl = $(this).data('complete-url');
            const cancelUrl = $(this).data('cancel-url');
            const paymentVerifyUrl = $(this).data('payment-verify-url');
            const paymentRejectUrl = $(this).data('payment-reject-url');

            // Populate text
            $('#bookingDetailModalLabel').text('Detail Booking #BKG-' + id);
            $('#modal-user-name').text(userName);
            $('#modal-user-email').text(userEmail);
            $('#modal-user-phone').text(userPhone);
            $('#modal-court-name').text(courtName);
            $('#modal-booking-time').text(date + ' | ' + time);
            $('#modal-booking-price').text(price);
            
            // Populate payment
            $('#modal-payment-status').text(paymentExists === 1 ? paymentStatus : 'Belum Bayar');
            let payBadgeClass = 'badge-secondary';
            if (paymentStatus === 'verified') payBadgeClass = 'badge-success';
            if (paymentStatus === 'pending') payBadgeClass = 'badge-warning';
            if (paymentStatus === 'rejected') payBadgeClass = 'badge-danger';
            $('#modal-payment-status').removeClass().addClass('badge text-uppercase ' + payBadgeClass);

            $('#modal-payment-amount').text(paymentAmount);
            $('#modal-payment-type').text(paymentType);

            // Proof of payment image
            if (paymentProof) {
                $('#modal-payment-proof-img').attr('src', paymentProof);
                $('#modal-payment-proof-link').attr('href', paymentProof);
                $('#modal-payment-proof-container').removeClass('d-none');
            } else {
                $('#modal-payment-proof-container').addClass('d-none');
            }

            // Timeline states
            resetTimeline();
            if (paymentStatus === 'verified') {
                setTimelineStepPaid(true);
            }
            if (status === 'approved' || status === 'confirmed' || status === 'completed') {
                setTimelineStepPaid(true);
                setTimelineStepConfirmed(true);
            }
            if (status === 'completed') {
                setTimelineStepPaid(true);
                setTimelineStepConfirmed(true);
                setTimelineStepCompleted(true);
            }

            // Action Buttons visibility and urls
            hideActionButtons();
            
            // 1. Cancel booking form (can cancel if not completed or already cancelled/rejected)
            if (status !== 'completed' && status !== 'cancelled' && status !== 'rejected') {
                $('#booking-cancel-form').attr('action', cancelUrl);
                $('#btn-cancel-booking').removeClass('d-none');
            }
            
            // 2. Reject Payment form (if payment pending)
            if (paymentExists === 1 && paymentStatus === 'pending') {
                $('#payment-reject-form').attr('action', paymentRejectUrl);
                $('#btn-reject-payment').removeClass('d-none');
            }
            
            // 3. Verify Payment / Approve Booking buttons
            if (paymentExists === 1 && paymentStatus === 'pending') {
                $('#payment-verify-form').attr('action', paymentVerifyUrl);
                $('#btn-verify-payment').removeClass('d-none');
            } else if (status === 'pending' && paymentExists === 0) {
                // Booking pending and no payment, can manually confirm
                $('#booking-approve-form').attr('action', approveUrl);
                $('#btn-approve-booking').removeClass('d-none');
            }
            
            // 4. Complete booking button
            if ((status === 'approved' || status === 'confirmed') && status !== 'completed') {
                $('#booking-complete-form').attr('action', completeUrl);
                $('#btn-complete-booking').removeClass('d-none');
            }

            $('#bookingDetailModal').modal('show');
        });

        function resetTimeline() {
            $('#step-paid').removeClass('active').find('.step-circle').removeClass('bg-emerald text-white').addClass('bg-gray-200 text-muted');
            $('#step-confirmed').removeClass('active').find('.step-circle').removeClass('bg-emerald text-white').addClass('bg-gray-200 text-muted');
            $('#step-completed').removeClass('active').find('.step-circle').removeClass('bg-emerald text-white').addClass('bg-gray-200 text-muted');
        }

        function setTimelineStepPaid(active) {
            if (active) {
                $('#step-paid').addClass('active').find('.step-circle').removeClass('bg-gray-200 text-muted').addClass('bg-emerald text-white').css('background-color', '#10b981');
            }
        }

        function setTimelineStepConfirmed(active) {
            if (active) {
                $('#step-confirmed').addClass('active').find('.step-circle').removeClass('bg-gray-200 text-muted').addClass('bg-emerald text-white').css('background-color', '#10b981');
            }
        }

        function setTimelineStepCompleted(active) {
            if (active) {
                $('#step-completed').addClass('active').find('.step-circle').removeClass('bg-gray-200 text-muted').addClass('bg-emerald text-white').css('background-color', '#10b981');
            }
        }

        function hideActionButtons() {
            $('#btn-reject-payment').addClass('d-none');
            $('#btn-cancel-booking').addClass('d-none');
            $('#btn-verify-payment').addClass('d-none');
            $('#btn-approve-booking').addClass('d-none');
            $('#btn-complete-booking').addClass('d-none');
        }

        // Action Buttons clicks inside Court Booking detail
        $('#btn-verify-payment').on('click', function() {
            $('#payment-verify-form').submit();
        });
        
        $('#btn-reject-payment').on('click', function() {
            $('#payment-reject-form').submit();
        });

        $('#btn-approve-booking').on('click', function() {
            $('#booking-approve-form').submit();
        });

        $('#btn-cancel-booking').on('click', function() {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Booking ini akan dibatalkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Batalkan!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#booking-cancel-form').submit();
                }
            });
        });

        $('#btn-complete-booking').on('click', function() {
            $('#booking-complete-form').submit();
        });


        // --- MEMBERSHIPS DETAIL MODAL POPULATE ---
        $('.view-membership-detail').on('click', function() {
            const id = $(this).data('id');
            const userName = $(this).data('user-name');
            const userEmail = $(this).data('user-email');
            const userPhone = $(this).data('user-phone');
            const packageName = $(this).data('package-name');
            const packagePrice = $(this).data('package-price');
            const start = $(this).data('start');
            const end = $(this).data('end');
            const status = $(this).data('status');
            
            const paymentExists = $(this).data('payment-exists');
            const paymentStatus = $(this).data('payment-status');
            const paymentAmount = $(this).data('payment-amount');
            const paymentType = $(this).data('payment-type');
            const paymentProof = $(this).data('payment-proof');
            
            const paymentVerifyUrl = $(this).data('payment-verify-url');
            const paymentRejectUrl = $(this).data('payment-reject-url');

            // Populate details
            $('#membershipDetailModalLabel').text('Detail Subscription #' + id);
            $('#modal-member-user-name').text(userName);
            $('#modal-member-user-email').text(userEmail);
            
            $('#modal-member-package').text(packageName);
            $('#modal-member-start').text(start);
            $('#modal-member-end').text(end);
            
            // Subscription status badge
            $('#modal-member-status').text(status);
            let subBadgeClass = 'badge-secondary';
            if (status === 'active') subBadgeClass = 'badge-success';
            if (status === 'pending') subBadgeClass = 'badge-warning';
            if (status === 'expired') subBadgeClass = 'badge-danger';
            $('#modal-member-status').removeClass().addClass('badge text-uppercase ' + subBadgeClass);

            // Payment status details
            $('#modal-member-payment-status').text(paymentExists === 1 ? paymentStatus : 'Belum Bayar');
            let subPayBadgeClass = 'badge-secondary';
            if (paymentStatus === 'verified') subPayBadgeClass = 'badge-success';
            if (paymentStatus === 'pending') subPayBadgeClass = 'badge-warning';
            if (paymentStatus === 'rejected') subPayBadgeClass = 'badge-danger';
            $('#modal-member-payment-status').removeClass().addClass('badge text-uppercase ' + subPayBadgeClass);
            
            $('#modal-member-payment-amount').text(paymentAmount);
            $('#modal-member-payment-type').text(paymentType);

            // Proof image
            if (paymentProof) {
                $('#modal-member-payment-proof-img').attr('src', paymentProof);
                $('#modal-member-payment-proof-link').attr('href', paymentProof);
                $('#modal-member-payment-proof-container').removeClass('d-none');
            } else {
                $('#modal-member-payment-proof-container').addClass('d-none');
            }

            // Actions buttons visibility
            $('#btn-member-verify-payment').addClass('d-none');
            $('#btn-member-reject-payment').addClass('d-none');

            if (paymentExists === 1 && paymentStatus === 'pending') {
                $('#member-payment-verify-form').attr('action', paymentVerifyUrl);
                $('#btn-member-verify-payment').removeClass('d-none');
                
                $('#member-payment-reject-form').attr('action', paymentRejectUrl);
                $('#btn-member-reject-payment').removeClass('d-none');
            }

            $('#membershipDetailModal').modal('show');
        });

        // Trigger verify/reject for member payment
        $('#btn-member-verify-payment').on('click', function() {
            $('#member-payment-verify-form').submit();
        });
        
        $('#btn-member-reject-payment').on('click', function() {
            $('#member-payment-reject-form').submit();
        });
    });
</script>
@endpush
