@extends('layouts.admin')

@section('title', 'Daftar Booking')
@section('header', 'Manajemen Booking & Pembayaran')

@section('content')
<div class="row">
    <div class="col-12">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible shadow-sm border-0">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-check"></i> Berhasil!</h5>
                {{ session('success') }}
            </div>
        @endif

        <div class="card card-outline card-primary shadow-sm border-0 overflow-hidden">
            <div class="card-header border-0 mt-2">
                <h3 class="card-title font-weight-bold text-uppercase small tracking-wider text-muted">Seluruh Riwayat Booking</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-valign-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th style="width: 50px" class="text-center border-0">#</th>
                                <th class="border-0">Pelanggan</th>
                                <th class="border-0">Lapangan</th>
                                <th class="border-0">Jadwal</th>
                                <th class="border-0">Total Harga</th>
                                <th class="border-0">Status</th>
                                <th class="text-center border-0">Pembayaran</th>
                                <th style="width: 180px" class="text-right border-0">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $booking)
                            <tr>
                                <td class="text-center text-muted small">{{ $loop->iteration }}</td>
                                <td class="font-weight-bold text-dark">{{ $booking->user->name }}</td>
                                <td class="text-muted">{{ $booking->court->name }}</td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="font-weight-bold">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</span>
                                        <small class="text-muted">{{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }} WIB</small>
                                    </div>
                                </td>
                                <td class="font-weight-bold text-primary">
                                    <small>Rp</small> {{ number_format($booking->total_price, 0, ',', '.') }}
                                </td>
                                <td>
                                    @if($booking->status == 'pending')
                                        <span class="badge badge-warning text-uppercase py-2 px-3 shadow-sm rounded-pill">Pending</span>
                                    @elseif($booking->status == 'approved')
                                        <span class="badge badge-success text-uppercase py-2 px-3 shadow-sm rounded-pill">Approved</span>
                                    @else
                                        <span class="badge badge-danger text-uppercase py-2 px-3 shadow-sm rounded-pill">{{ strtoupper($booking->status) }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($booking->payment)
                                        <div class="d-flex flex-column align-items-center">
                                            @php
                                                $paymentClass = 'badge-info';
                                                if($booking->payment->status == 'verified') $paymentClass = 'badge-success';
                                                if($booking->payment->status == 'rejected') $paymentClass = 'badge-danger';
                                            @endphp
                                            <span class="badge {{ $paymentClass }} text-uppercase py-1 px-2 mb-1 shadow-sm">
                                                {{ $booking->payment->status }}
                                            </span>
                                            @if($booking->payment->proof_of_payment)
                                                <a href="{{ asset('storage/' . $booking->payment->proof_of_payment) }}" target="_blank" class="text-xs font-weight-bold text-primary mt-1 border-bottom border-primary" style="font-size: 10px; line-height: 1;">
                                                    <i class="fas fa-search mr-1"></i>LIHAT BUKTI
                                                </a>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted small italic opacity-50">Belum Ada</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <div class="btn-group shadow-sm rounded overflow-hidden">
                                        @if($booking->status == 'pending')
                                            <form action="{{ route('admin.bookings.approve', $booking) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm px-3" title="Setujui Booking">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.bookings.cancel', $booking) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm px-3 border-left" title="Batalkan Booking">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if($booking->payment && $booking->payment->status == 'pending')
                                            <form action="{{ route('admin.payments.verify', $booking->payment) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-info btn-sm px-3 border-left" title="Verifikasi Pembayaran">
                                                    <i class="fas fa-file-invoice-dollar"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.payments.reject', $booking->payment) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-warning btn-sm px-3 border-left text-white" title="Tolak Pembayaran">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                    @if($booking->status != 'pending' && !($booking->payment && $booking->payment->status == 'pending'))
                                        <span class="text-muted small opacity-50"><i class="fas fa-lock"></i></span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="py-5 text-center text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2 d-block opacity-25"></i>
                                    Belum ada data booking yang tercatat.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
