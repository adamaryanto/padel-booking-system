@extends('layouts.admin')

@section('title', 'Edit FAQ')
@section('header', 'FAQ Management')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">FAQ Details</h3>
            </div>
            <form action="{{ route('admin.faqs.update', $faq) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label for="question">Question</label>
                        <input type="text" name="question" class="form-control @error('question') is-invalid @enderror" id="question" value="{{ old('question', $faq->question) }}" required>
                        @error('question')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="answer">Answer</label>
                        <textarea name="answer" class="form-control @error('answer') is-invalid @enderror" id="answer" rows="5" required>{{ old('answer', $faq->answer) }}</textarea>
                        @error('answer')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="order">Order</label>
                        <input type="number" name="order" class="form-control @error('order') is-invalid @enderror" id="order" value="{{ old('order', $faq->order) }}" required>
                        @error('order')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-info">Update</button>
                    <a href="{{ route('admin.faqs.index') }}" class="btn btn-default">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
