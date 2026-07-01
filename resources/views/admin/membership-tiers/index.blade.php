@extends('layouts.admin')

@section('title', 'Manage Membership Tiers')
@section('header', 'Membership Tiers')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-outline card-primary shadow-sm">
            <div class="card-header">
                <h3 class="card-title">List Tiers</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.membership-tiers.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Add New Tier
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-hover text-nowrap mb-0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Disc (WD/WE)</th>
                                <th>Limit (WD/WE)</th>
                                <th>Window</th>
                                <th>Duration</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tiers as $tier)
                            <tr>
                                <td>{{ $tier->name }}</td>
                                <td>Rp {{ number_format($tier->price, 0, ',', '.') }}</td>
                                <td>{{ $tier->discount_weekday }}% / {{ $tier->discount_weekend }}%</td>
                                <td>{{ $tier->discount_weekday_limit ? $tier->discount_weekday_limit.'x' : '∞' }} / {{ $tier->discount_weekend_limit ? $tier->discount_weekend_limit.'x' : '∞' }}</td>
                                <td>{{ $tier->booking_window_days }} Days</td>
                                <td>{{ $tier->duration_days }} Days</td>
                                <td>
                                    @if($tier->is_active)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.membership-tiers.edit', $tier) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.membership-tiers.destroy', $tier) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">No tiers found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile View Card List -->
                <div class="d-block d-md-none p-3 bg-light">
                    @forelse($tiers as $tier)
                    <div class="card p-3 mb-3 bg-white shadow-sm" style="border-radius: 1rem !important; border: 1px solid #e5e7eb !important;">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h5 class="font-weight-bold text-dark mb-1" style="font-size: 0.95rem;">{{ $tier->name }}</h5>
                                <span class="text-success font-weight-bold" style="font-size: 0.85rem;">Rp {{ number_format($tier->price, 0, ',', '.') }}</span>
                            </div>
                            <div>
                                @if($tier->is_active)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-danger">Inactive</span>
                                @endif
                            </div>
                        </div>
                        <div class="border-top border-bottom py-2 my-2 text-muted" style="font-size: 0.8rem;">
                            <div class="mb-1"><i class="fas fa-percentage mr-2 text-info"></i><strong>Weekday Disc:</strong> {{ $tier->discount_weekday }}% (Limit: {{ $tier->discount_weekday_limit ? $tier->discount_weekday_limit.'x' : '∞' }})</div>
                            <div class="mb-1"><i class="fas fa-percentage mr-2 text-info"></i><strong>Weekend Disc:</strong> {{ $tier->discount_weekend }}% (Limit: {{ $tier->discount_weekend_limit ? $tier->discount_weekend_limit.'x' : '∞' }})</div>
                            <div class="mb-1"><i class="far fa-calendar-alt mr-2"></i><strong>Booking Window:</strong> {{ $tier->booking_window_days }} Days</div>
                            <div><i class="far fa-clock mr-2"></i><strong>Duration:</strong> {{ $tier->duration_days }} Days</div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.membership-tiers.edit', $tier) }}" class="btn btn-info btn-sm tier-mobile-btn mr-2">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>
                            <form action="{{ route('admin.membership-tiers.destroy', $tier) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm tier-mobile-btn" onclick="return confirm('Are you sure?')">
                                    <i class="fas fa-trash mr-1"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="py-5 text-center text-muted">
                        No tiers found.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    @media (max-width: 767.98px) {
        .tier-mobile-btn {
            padding: 0.35rem 0.65rem !important;
            font-size: 0.75rem !important;
            border-radius: 0.5rem !important;
            height: auto !important;
            line-height: 1.2 !important;
            font-weight: 600 !important;
        }
    }
</style>
@endpush
@endsection
