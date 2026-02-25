@extends('layouts.admin')

@section('title', 'Edit Membership Tier')
@section('header', 'Edit Tier')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Tier Details</h3>
            </div>
            <form action="{{ route('admin.membership-tiers.update', $membershipTier) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" id="name" value="{{ $membershipTier->name }}" required>
                    </div>
                    <div class="form-group">
                        <label for="price">Price (Rp)</label>
                        <input type="number" name="price" class="form-control" id="price" value="{{ (int)$membershipTier->price }}" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="discount_weekday">Discount Weekday (%)</label>
                                <input type="number" name="discount_weekday" class="form-control" id="discount_weekday" value="{{ $membershipTier->discount_weekday }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="discount_weekend">Discount Weekend (%)</label>
                                <input type="number" name="discount_weekend" class="form-control" id="discount_weekend" value="{{ $membershipTier->discount_weekend }}" required>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="discount_percentage" value="{{ $membershipTier->discount_percentage }}">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="duration_days">Duration (Days)</label>
                                <input type="number" name="duration_days" class="form-control" id="duration_days" value="{{ $membershipTier->duration_days }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="booking_window_days">Booking Window (Days)</label>
                                <input type="number" name="booking_window_days" class="form-control" id="booking_window_days" value="{{ $membershipTier->booking_window_days }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description">Description (Benefits)</label>
                        <textarea name="description" class="form-control" id="description" rows="4">{{ $membershipTier->description }}</textarea>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="is_active" class="custom-control-input" id="is_active" value="1" {{ $membershipTier->is_active ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_active">Is Active</label>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-info">Update</button>
                    <a href="{{ route('admin.membership-tiers.index') }}" class="btn btn-default">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
