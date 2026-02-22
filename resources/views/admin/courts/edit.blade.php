@extends('layouts.admin')

@section('title', 'Edit Lapangan')
@section('header', 'Edit Detail Lapangan')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card card-outline card-info shadow-sm border-0">
            <div class="card-header border-0 mt-2">
                <h3 class="card-title font-weight-bold text-muted small text-uppercase tracking-wider">
                    <i class="fas fa-edit mr-2 text-info"></i>Formulir Perubahan Lapangan
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.courts.update', $court) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group mb-4">
                        <label for="name" class="text-dark font-weight-bold">Nama Lapangan</label>
                        <input type="text" name="name" id="name" class="form-control form-control-lg border-0 bg-light rounded shadow-none @error('name') is-invalid @enderror" value="{{ old('name', $court->name) }}" required>
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
                                        <span class="input-group-text border-0 bg-info text-white font-weight-bold">Rp</span>
                                    </div>
                                    <input type="number" name="price_per_hour" id="price_per_hour" class="form-control form-control-lg border-0 bg-light rounded shadow-none @error('price_per_hour') is-invalid @enderror" value="{{ old('price_per_hour', $court->price_per_hour) }}" required>
                                </div>
                                @error('price_per_hour')
                                    <div class="invalid-feedback d-block font-weight-bold small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label for="is_active" class="text-dark font-weight-bold">Status Saat Ini</label>
                                <select name="is_active" id="is_active" class="form-control form-control-lg border-0 bg-light rounded shadow-none custom-select">
                                    <option value="1" {{ old('is_active', $court->is_active) == '1' ? 'selected' : '' }}>AKTIF / TERSEDIA</option>
                                    <option value="0" {{ old('is_active', $court->is_active) == '0' ? 'selected' : '' }}>NONAKTIF</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label for="description" class="text-dark font-weight-bold">Deskripsi Lapangan</label>
                        <textarea name="description" id="description" rows="4" class="form-control border-0 bg-light rounded shadow-none @error('description') is-invalid @enderror">{{ old('description', $court->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback font-weight-bold">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-5">
                        <label for="photo" class="text-dark font-weight-bold d-block">Update Foto Lapangan</label>
                        <div class="row align-items-center">
                            @if($court->photo)
                            <div class="col-md-4 mb-3 mb-md-0 text-center">
                                <div class="p-2 bg-white rounded shadow-sm h-100 d-flex align-items-center justify-content-center border" style="height: 150px !important;">
                                    <img src="{{ asset('storage/' . $court->photo) }}" alt="{{ $court->name }}" class="img-fluid rounded" style="max-height: 100%;">
                                </div>
                                <p class="text-muted small mt-2 mb-0 font-italic">Foto Saat Ini</p>
                            </div>
                            @endif
                            <div class="{{ $court->photo ? 'col-md-8' : 'col-md-12' }}">
                                <div class="p-4 rounded-lg bg-light border-2 border-dashed border-gray d-flex flex-column align-items-center justify-content-center text-center">
                                    <i class="fas fa-image fa-2x text-muted mb-2"></i>
                                    <div class="custom-file" style="max-width: 300px;">
                                        <input type="file" name="photo" class="custom-file-input" id="photo">
                                        <label class="custom-file-label border-0 text-left rounded shadow-sm" for="photo">Ganti foto...</label>
                                    </div>
                                    <p class="text-muted small mt-2 mb-0 font-weight-light italic">Biarkan kosong jika tidak ingin diubah.</p>
                                    @error('photo')
                                        <div class="text-danger font-weight-bold small mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <a href="{{ route('admin.courts.index') }}" class="btn btn-link text-muted font-weight-bold px-0">
                            <i class="fas fa-times mr-1"></i> Batal Ubah
                        </a>
                        <button type="submit" class="btn btn-info btn-lg shadow rounded-pill px-5 font-weight-bold text-uppercase small tracking-widest">
                            SIMPAN PERUBAHAN
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
