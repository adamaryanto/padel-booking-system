@extends('layouts.admin')

@section('title', 'Tambah Lapangan')
@section('header', 'Tambah Lapangan Baru')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card card-outline card-primary shadow-sm border-0">
            <div class="card-header border-0 mt-2">
                <h3 class="card-title font-weight-bold text-muted small text-uppercase tracking-wider">
                    <i class="fas fa-plus-circle mr-2 text-primary"></i>Formulir Data Lapangan
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.courts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="form-group mb-4">
                        <label for="name" class="text-dark font-weight-bold">Nama Lapangan</label>
                        <input type="text" name="name" id="name" class="form-control form-control-lg border-0 bg-light rounded shadow-none @error('name') is-invalid @enderror" placeholder="Contoh: Lapangan A" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback font-weight-bold">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label for="price_per_hour" class="text-dark font-weight-bold">Harga Per Jam</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text border-0 bg-primary text-white font-weight-bold">Rp</span>
                                    </div>
                                    <input type="number" name="price_per_hour" id="price_per_hour" class="form-control form-control-lg border-0 bg-light rounded shadow-none @error('price_per_hour') is-invalid @enderror" value="{{ old('price_per_hour') }}" required>
                                </div>
                                @error('price_per_hour')
                                    <div class="invalid-feedback d-block font-weight-bold small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label for="is_active" class="text-dark font-weight-bold">Status Lapangan</label>
                                <select name="is_active" id="is_active" class="form-control form-control-lg border-0 bg-light rounded shadow-none custom-select">
                                    <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>AKTIF / TERSEDIA</option>
                                    <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>NONAKTIF</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label for="description" class="text-dark font-weight-bold">Deskripsi Tambahan</label>
                        <textarea name="description" id="description" rows="4" class="form-control border-0 bg-light rounded shadow-none @error('description') is-invalid @enderror" placeholder="Ceritakan fitur atau detail lapangan ini...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback font-weight-bold">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-5">
                        <label for="photo" class="text-dark font-weight-bold d-block">Unggah Foto Lapangan</label>
                        <div class="p-4 rounded-lg bg-light border-2 border-dashed border-gray d-flex flex-column align-items-center justify-content-center text-center">
                            <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                            <div class="custom-file" style="max-width: 300px;">
                                <input type="file" name="photo" class="custom-file-input" id="photo">
                                <label class="custom-file-label border-0 text-left rounded shadow-sm" for="photo">Pilih file...</label>
                            </div>
                            <p class="text-muted small mt-3 mb-0">Max: 2MB, Format: JPG, PNG, WEBP</p>
                            @error('photo')
                                <div class="text-danger font-weight-bold small mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('admin.courts.index') }}" class="btn btn-link text-muted font-weight-bold">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar
                        </a>
                        <button type="submit" class="btn btn-primary btn-lg shadow rounded-pill px-5 font-weight-bold text-uppercase small tracking-widest">
                            SIMPAN LAPANGAN
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
    });
</script>
@endpush
