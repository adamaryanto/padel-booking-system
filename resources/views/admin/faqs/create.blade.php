@extends('layouts.admin')

@section('title', 'Create FAQ')
@section('header', 'FAQ Management')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">FAQ Details</h3>
            </div>
            <form action="{{ route('admin.faqs.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="question">Question</label>
                        <input type="text" name="question" class="form-control @error('question') is-invalid @enderror" id="question" placeholder="E.g., Bagaimana cara memesan lapangan?" value="{{ old('question') }}" required>
                        @error('question')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="answer">Answer</label>
                        <textarea name="answer" class="form-control @error('answer') is-invalid @enderror" id="answer" rows="5" placeholder="Tulis jawaban lengkap..." required>{{ old('answer') }}</textarea>
                        @error('answer')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="order">Order</label>
                        <input type="number" name="order" class="form-control @error('order') is-invalid @enderror" id="order" value="{{ old('order', 0) }}" required>
                        @error('order')
                            <span class="error invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">Example: 0 or 1 will appear first.</small>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <a href="{{ route('admin.faqs.index') }}" class="btn btn-default">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
