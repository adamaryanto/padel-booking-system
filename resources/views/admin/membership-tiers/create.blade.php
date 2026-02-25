@extends('layouts.admin')

@section('title', 'Create Membership Tier')
@section('header', 'Create Tier')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Tier Details</h3>
            </div>
            <form action="{{ route('admin.membership-tiers.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" id="name" placeholder="Pro Member" required>
                    </div>
                    <div class="form-group">
                        <label for="price">Price (Rp)</label>
                        <input type="number" name="price" class="form-control" id="price" placeholder="899000" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="discount_weekday">Discount Weekday (%)</label>
                                <input type="number" name="discount_weekday" class="form-control" id="discount_weekday" placeholder="10" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="discount_weekend">Discount Weekend (%)</label>
                                <input type="number" name="discount_weekend" class="form-control" id="discount_weekend" placeholder="20" required>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="discount_percentage" value="0">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="duration_days">Duration (Days)</label>
                                <input type="number" name="duration_days" class="form-control" id="duration_days" placeholder="30" value="30" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="booking_window_days">Booking Window (Days)</label>
                                <input type="number" name="booking_window_days" class="form-control" id="booking_window_days" placeholder="7" value="7" required>
                                <small class="form-text text-muted">Example: 7 means can book up to 7 days ahead.</small>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description">Description (Benefits)</label>
                        <textarea name="description" class="form-control" id="description" rows="4" placeholder="- Diskon 20%&#10;- Free 2 jam main&#10;- Prioritas slot prime time"></textarea>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <a href="{{ route('admin.membership-tiers.index') }}" class="btn btn-default">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
