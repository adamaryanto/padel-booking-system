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
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Disc (WD/WE)</th>
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
                            <td colspan="6" class="text-center">No tiers found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
