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
                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <label for="price_per_hour" class="text-dark font-weight-bold">Harga Umum / Jam</label>
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
                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <label for="price_weekday" class="text-dark font-weight-bold">Harga Weekday</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text border-0 bg-success text-white font-weight-bold">Rp</span>
                                    </div>
                                    <input type="number" name="price_weekday" id="price_weekday" class="form-control form-control-lg border-0 bg-light rounded shadow-none @error('price_weekday') is-invalid @enderror" value="{{ old('price_weekday') }}">
                                </div>
                                @error('price_weekday')
                                    <div class="invalid-feedback d-block font-weight-bold small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <label for="price_weekend" class="text-dark font-weight-bold">Harga Weekend</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text border-0 bg-warning text-white font-weight-bold">Rp</span>
                                    </div>
                                    <input type="number" name="price_weekend" id="price_weekend" class="form-control form-control-lg border-0 bg-light rounded shadow-none @error('price_weekend') is-invalid @enderror" value="{{ old('price_weekend') }}">
                                </div>
                                @error('price_weekend')
                                    <div class="invalid-feedback d-block font-weight-bold small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <label for="open_time" class="text-dark font-weight-bold">Jam Buka</label>
                                <input type="time" name="open_time" id="open_time" class="form-control form-control-lg border-0 bg-light rounded shadow-none @error('open_time') is-invalid @enderror" value="{{ old('open_time', '06:00') }}">
                                @error('open_time')
                                    <div class="invalid-feedback font-weight-bold">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <label for="close_time" class="text-dark font-weight-bold">Jam Tutup</label>
                                <input type="time" name="close_time" id="close_time" class="form-control form-control-lg border-0 bg-light rounded shadow-none @error('close_time') is-invalid @enderror" value="{{ old('close_time', '22:00') }}">
                                @error('close_time')
                                    <div class="invalid-feedback font-weight-bold">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
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
                        <label for="member_promo" class="text-dark font-weight-bold">Promo Member</label>
                        <input type="text" name="member_promo" id="member_promo" class="form-control border-0 bg-light rounded shadow-none @error('member_promo') is-invalid @enderror" placeholder="Contoh: Diskon 10% untuk member Gold" value="{{ old('member_promo') }}">
                        @error('member_promo')
                            <div class="invalid-feedback font-weight-bold">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label for="description" class="text-dark font-weight-bold">Deskripsi Tambahan</label>
                        <textarea name="description" id="description" rows="3" class="form-control border-0 bg-light rounded shadow-none @error('description') is-invalid @enderror" placeholder="Ceritakan fitur atau detail lapangan ini...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback font-weight-bold">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label for="facilities" class="text-dark font-weight-bold">Fasilitas</label>
                        <textarea name="facilities" id="facilities" rows="2" class="form-control border-0 bg-light rounded shadow-none @error('facilities') is-invalid @enderror" placeholder="Contoh: Digital Lighting, Ultra Grip, Cafe, Parkir (Pisahkan dengan koma)">{{ old('facilities') }}</textarea>
                        @error('facilities')
                            <div class="invalid-feedback font-weight-bold">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Gunakan tanda koma (,) untuk memisahkan antar fasilitas.</small>
                    </div>

                    <div class="form-group mb-4">
                        <label for="photo" class="text-dark font-weight-bold d-block">Foto Utama</label>
                        <div class="custom-file">
                            <input type="file" name="photo" class="custom-file-input" id="photo">
                            <label class="custom-file-label border-0 bg-light rounded shadow-none" for="photo">Pilih file...</label>
                        </div>
                        @error('photo')
                            <div class="text-danger font-weight-bold small mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-5">
                        <label for="additional_photos" class="text-dark font-weight-bold d-block">Foto Tambahan (Bisa lebih dari 1)</label>
                        <div class="custom-file">
                            <input type="file" name="additional_photos[]" class="custom-file-input" id="additional_photos" multiple>
                            <label class="custom-file-label border-0 bg-light rounded shadow-none" for="additional_photos">Pilih file-file...</label>
                        </div>
                        <small class="text-muted">Anda dapat memilih beberapa foto sekaligus.</small>
                        @error('additional_photos.*')
                            <div class="text-danger font-weight-bold small mt-2">{{ $message }}</div>
                        @enderror
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
