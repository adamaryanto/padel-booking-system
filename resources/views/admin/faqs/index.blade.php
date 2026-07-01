@extends('layouts.admin')

@section('title', 'Manage FAQs')
@section('header', 'FAQ Management')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-outline card-primary shadow-sm">
            <div class="card-header">
                <h3 class="card-title">FAQ List</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.faqs.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Add New FAQ
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-hover text-nowrap mb-0">
                        <thead>
                            <tr>
                                <th>Order</th>
                                <th>Pertanyaan</th>
                                <th>Jawaban</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($faqs as $faq)
                            <tr>
                                <td>{{ $faq->order }}</td>
                                <td>{{ Str::limit($faq->question, 50) }}</td>
                                <td>{{ Str::limit($faq->answer, 70) }}</td>
                                <td>
                                    <a href="{{ route('admin.faqs.edit', $faq) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.faqs.destroy', $faq) }}" method="POST" class="d-inline delete-confirm">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">No FAQs found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile View Card List -->
                <div class="d-block d-md-none p-3 bg-light">
                    @forelse($faqs as $faq)
                    <div class="card p-3 mb-3 bg-white shadow-sm" style="border-radius: 1rem !important; border: 1px solid #e5e7eb !important;">
                        <div class="d-flex justify-content-between align-items-center pb-2 mb-2 border-bottom">
                            <div>
                                <span class="badge badge-info px-2.5 py-1" style="font-size: 0.75rem;">Order: #{{ $faq->order }}</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <a href="{{ route('admin.faqs.edit', $faq) }}" class="btn btn-info btn-sm faq-mobile-btn mr-2">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                                <form action="{{ route('admin.faqs.destroy', $faq) }}" method="POST" class="d-inline delete-confirm">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm faq-mobile-btn">
                                        <i class="fas fa-trash mr-1"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="py-1" style="font-size: 0.85rem;">
                            <div class="mb-2">
                                <strong class="text-dark d-block mb-1"><i class="fas fa-question-circle mr-1 text-primary"></i> Pertanyaan:</strong>
                                <div class="text-muted bg-light p-2 rounded" style="white-space: normal; font-size: 0.825rem; line-height: 1.4;">
                                    {{ $faq->question }}
                                </div>
                            </div>
                            <div>
                                <strong class="text-dark d-block mb-1"><i class="fas fa-comment-dots mr-1 text-success"></i> Jawaban:</strong>
                                <div class="text-muted bg-light p-2 rounded" style="white-space: normal; font-size: 0.825rem; line-height: 1.4;">
                                    {{ $faq->answer }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="py-5 text-center text-muted">
                        No FAQs found.
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
        .faq-mobile-btn {
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
