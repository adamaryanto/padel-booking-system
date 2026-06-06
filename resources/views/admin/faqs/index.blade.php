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
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
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
        </div>
    </div>
</div>
@endsection
