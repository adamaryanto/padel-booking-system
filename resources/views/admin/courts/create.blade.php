@extends('layouts.admin')

@section('title', 'Tambah Lapangan')
@section('header', 'Tambah Lapangan Baru')

@section('content')
<div class="mb-4 mt-n2">
    <p class="text-muted text-sm">Tambahkan lapangan padel baru beserta informasi detail dan harga operasionalnya.</p>
</div>

<div class="row">
    <div class="col-lg-9 col-xl-8">
        <form action="{{ route('admin.courts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- Basic Information Card -->
            <div class="card border-gray-200 bg-white mb-4" style="border-radius: 1.5rem !important;">
                <div class="card-header border-0 bg-transparent pt-4 px-4 pb-0">
                    <h5 class="font-weight-bold text-dark mb-0">Informasi Dasar</h5>
                </div>
                <div class="card-body p-4">
                    <!-- Court Name -->
                    <div class="form-group mb-4">
                        <label for="name" class="font-weight-bold text-xs uppercase tracking-wider text-muted mb-2 d-block">Nama Lapangan <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control rounded-xl @error('name') is-invalid @enderror" placeholder="Contoh: Elite Padel Arena" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Location -->
                    <div class="form-group mb-4">
                        <label for="location" class="font-weight-bold text-xs uppercase tracking-wider text-muted mb-2 d-block">Lokasi <span class="text-danger">*</span></label>
                        <input type="text" name="location" id="location" class="form-control rounded-xl @error('location') is-invalid @enderror" placeholder="Contoh: Jakarta Selatan" value="{{ old('location') }}" required>
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="form-group mb-4">
                        <label for="description" class="font-weight-bold text-xs uppercase tracking-wider text-muted mb-2 d-block">Deskripsi</label>
                        <textarea name="description" id="description" rows="4" class="form-control rounded-xl @error('description') is-invalid @enderror" placeholder="Ceritakan detail lapangan, keunikan, atau informasi penting lainnya...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Facilities -->
                    <div class="form-group mb-0">
                        <label for="facilities" class="font-weight-bold text-xs uppercase tracking-wider text-muted mb-2 d-block">Fasilitas Tambahan</label>
                        <textarea name="facilities" id="facilities" rows="2" class="form-control rounded-xl @error('facilities') is-invalid @enderror" placeholder="Contoh: Ruang Ganti, Area Parkir, Kafe & Lounge, Shower Area (pisahkan dengan koma)">{{ old('facilities') }}</textarea>
                        @error('facilities')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted text-xs mt-1 d-block">Pisahkan antar fasilitas menggunakan tanda koma (,).</small>
                    </div>
                </div>
            </div>

            <!-- Pricing & Operations Card -->
            <div class="card border-gray-200 bg-white mb-4" style="border-radius: 1.5rem !important;">
                <div class="card-header border-0 bg-transparent pt-4 px-4 pb-0">
                    <h5 class="font-weight-bold text-dark mb-0">Harga & Operasional</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <!-- Price Per Hour (Default placeholder/optional) -->
                        <div class="col-md-4 mb-4">
                            <div class="form-group mb-0">
                                <label for="price_per_hour" class="font-weight-bold text-xs uppercase tracking-wider text-muted mb-2 d-block">Price Per Hour (Base)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light border-right-0 border-gray-200" style="border-radius: 0.75rem 0 0 0.75rem;">Rp</span>
                                    </div>
                                    <input type="number" name="price_per_hour" id="price_per_hour" class="form-control rounded-xl border-left-0 @error('price_per_hour') is-invalid @enderror" placeholder="150000" value="{{ old('price_per_hour') }}">
                                </div>
                                @error('price_per_hour')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Price Weekday -->
                        <div class="col-md-4 mb-4">
                            <div class="form-group mb-0">
                                <label for="price_weekday" class="font-weight-bold text-xs uppercase tracking-wider text-muted mb-2 d-block">Harga Weekday <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light border-right-0 border-gray-200" style="border-radius: 0.75rem 0 0 0.75rem;">Rp</span>
                                    </div>
                                    <input type="number" name="price_weekday" id="price_weekday" class="form-control rounded-xl border-left-0 @error('price_weekday') is-invalid @enderror" placeholder="150000" value="{{ old('price_weekday') }}" required>
                                </div>
                                @error('price_weekday')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Price Weekend -->
                        <div class="col-md-4 mb-4">
                            <div class="form-group mb-0">
                                <label for="price_weekend" class="font-weight-bold text-xs uppercase tracking-wider text-muted mb-2 d-block">Harga Weekend <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light border-right-0 border-gray-200" style="border-radius: 0.75rem 0 0 0.75rem;">Rp</span>
                                    </div>
                                    <input type="number" name="price_weekend" id="price_weekend" class="form-control rounded-xl border-left-0 @error('price_weekend') is-invalid @enderror" placeholder="175000" value="{{ old('price_weekend') }}" required>
                                </div>
                                @error('price_weekend')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Open Time -->
                        <div class="col-md-6 mb-4 mb-md-0">
                            <div class="form-group mb-0">
                                <label for="open_time" class="font-weight-bold text-xs uppercase tracking-wider text-muted mb-2 d-block">Jam Buka</label>
                                <input type="time" name="open_time" id="open_time" class="form-control rounded-xl @error('open_time') is-invalid @enderror" value="{{ old('open_time', '06:00') }}">
                                @error('open_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Close Time -->
                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label for="close_time" class="font-weight-bold text-xs uppercase tracking-wider text-muted mb-2 d-block">Jam Tutup</label>
                                <input type="time" name="close_time" id="close_time" class="form-control rounded-xl @error('close_time') is-invalid @enderror" value="{{ old('close_time', '22:00') }}">
                                @error('close_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Court Attributes Card -->
            <div class="card border-gray-200 bg-white mb-4" style="border-radius: 1.5rem !important;">
                <div class="card-header border-0 bg-transparent pt-4 px-4 pb-0">
                    <h5 class="font-weight-bold text-dark mb-0">Tipe & Status</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <!-- Court Type -->
                        <div class="col-md-6 mb-4 mb-md-0">
                            <div class="form-group mb-0">
                                <label for="court_type" class="font-weight-bold text-xs uppercase tracking-wider text-muted mb-2 d-block">Court Type <span class="text-danger">*</span></label>
                                <select name="court_type" id="court_type" class="form-control rounded-xl custom-select" required>
                                    <option value="Indoor" {{ old('court_type') == 'Indoor' ? 'selected' : '' }}>Indoor</option>
                                    <option value="Outdoor" {{ old('court_type', 'Outdoor') == 'Outdoor' ? 'selected' : '' }}>Outdoor</option>
                                </select>
                            </div>
                        </div>

                        <!-- Court Status -->
                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label for="is_active" class="font-weight-bold text-xs uppercase tracking-wider text-muted mb-2 d-block">Court Status</label>
                                <select name="is_active" id="is_active" class="form-control rounded-xl custom-select">
                                    <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Member Promo (optional but available in database) -->
            <div class="card border-gray-200 bg-white mb-4" style="border-radius: 1.5rem !important;">
                <div class="card-header border-0 bg-transparent pt-4 px-4 pb-0">
                    <h5 class="font-weight-bold text-dark mb-0">Promo Member</h5>
                </div>
                <div class="card-body p-4">
                    <div class="form-group mb-0">
                        <label for="member_promo" class="font-weight-bold text-xs uppercase tracking-wider text-muted mb-2 d-block">Promo Khusus Member</label>
                        <input type="text" name="member_promo" id="member_promo" class="form-control rounded-xl @error('member_promo') is-invalid @enderror" placeholder="Contoh: Diskon 10% untuk Silver Member" value="{{ old('member_promo') }}">
                        @error('member_promo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Image Upload Card -->
            <div class="card border-gray-200 bg-white mb-4" style="border-radius: 1.5rem !important;">
                <div class="card-header border-0 bg-transparent pt-4 px-4 pb-0">
                    <h5 class="font-weight-bold text-dark mb-0">Foto Lapangan</h5>
                </div>
                <div class="card-body p-4">
                    <!-- Main Photo -->
                    <div class="form-group mb-4">
                        <label for="photo" class="font-weight-bold text-xs uppercase tracking-wider text-muted mb-2 d-block">Foto Utama</label>
                        <div class="custom-file">
                            <input type="file" name="photo" class="custom-file-input" id="photo" accept="image/*">
                            <label class="custom-file-label rounded-xl" for="photo">Pilih Foto Utama...</label>
                        </div>
                        @error('photo')
                            <div class="text-danger font-weight-bold small mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Additional Photos -->
                    <div class="form-group mb-0">
                        <label class="font-weight-bold text-xs uppercase tracking-wider text-muted mb-3 d-block">Foto Tambahan (Maksimal 3)</label>
                        <div class="row">
                            @for($i = 1; $i <= 3; $i++)
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <div class="card h-100 border-2 border-dashed rounded-xl position-relative overflow-hidden additional-photo-box bg-light" 
                                         id="additional-photo-box-{{ $i }}" 
                                         style="min-height: 140px; cursor: pointer; border-style: dashed !important; border-width: 2px !important; border-color: #e5e7eb !important;"
                                         onclick="triggerFileInput({{ $i }})">
                                        
                                        <!-- Image Preview -->
                                        <img id="additional-photo-preview-{{ $i }}" 
                                             src="#" 
                                             alt="Foto {{ $i }}" 
                                             class="img-fluid w-100 h-100 d-none" 
                                             style="object-fit: cover; position: absolute; top: 0; left: 0;">
                                        
                                        <!-- Overlay / Placeholder -->
                                        <div id="additional-photo-overlay-{{ $i }}" 
                                             class="w-100 h-100 position-absolute d-flex flex-column align-items-center justify-content-center" 
                                             style="top: 0; left: 0; background: rgba(0,0,0,0.02); color: #9ca3af; transition: background 0.3s;">
                                            <i class="fas fa-plus mb-2" style="font-size: 1.25rem;"></i>
                                            <span class="small font-weight-bold text-uppercase tracking-wider" id="additional-photo-text-{{ $i }}" style="font-size: 0.65rem;">
                                                Upload Foto {{ $i }}
                                            </span>
                                        </div>

                                        <!-- Delete Button -->
                                        <button type="button" 
                                                class="btn btn-danger btn-sm position-absolute rounded-circle shadow-sm d-none" 
                                                style="top: 8px; right: 8px; z-index: 10; width: 28px; height: 28px; padding: 0; display: flex; align-items: center; justify-content: center;" 
                                                id="additional-photo-remove-btn-{{ $i }}"
                                                onclick="removePhoto(event, {{ $i }})">
                                            <i class="fas fa-trash-alt text-white" style="font-size: 0.75rem;"></i>
                                        </button>
                                    </div>
                                    
                                    <!-- Hidden inputs -->
                                    <input type="file" 
                                           name="additional_photo_{{ $i }}" 
                                           id="additional-photo-input-{{ $i }}" 
                                           class="d-none" 
                                           accept="image/*" 
                                           onchange="previewAdditionalPhoto(this, {{ $i }})">
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="d-flex justify-content-between align-items-center mb-5">
                <a href="{{ route('admin.courts.index') }}" class="text-muted font-weight-bold text-sm">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
                </a>
                <button type="submit" class="btn btn-primary px-5 py-2.5 rounded-xl font-weight-bold shadow-sm">
                    Save Court
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
    .additional-photo-box:hover {
        border-color: #10b981 !important;
        background-color: rgba(16, 185, 129, 0.02) !important;
    }
    .additional-photo-box:hover #additional-photo-overlay-1,
    .additional-photo-box:hover #additional-photo-overlay-2,
    .additional-photo-box:hover #additional-photo-overlay-3 {
        color: #10b981 !important;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function () {
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
    });

    function triggerFileInput(slot) {
        document.getElementById('additional-photo-input-' + slot).click();
    }

    function previewAdditionalPhoto(input, slot) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewImg = document.getElementById('additional-photo-preview-' + slot);
                const overlay = document.getElementById('additional-photo-overlay-' + slot);
                const text = document.getElementById('additional-photo-text-' + slot);
                const icon = overlay.querySelector('i');
                const removeBtn = document.getElementById('additional-photo-remove-btn-' + slot);

                // Update preview image
                previewImg.src = e.target.result;
                previewImg.classList.remove('d-none');

                // Update overlay
                overlay.style.background = 'rgba(0,0,0,0.4)';
                overlay.style.color = '#ffffff';
                text.textContent = 'Ganti Foto';
                if (icon) {
                    icon.className = 'fas fa-edit mb-2';
                }

                // Show remove button
                if (removeBtn) {
                    removeBtn.classList.remove('d-none');
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removePhoto(event, slot) {
        event.stopPropagation(); // prevent file input click

        const previewImg = document.getElementById('additional-photo-preview-' + slot);
        const overlay = document.getElementById('additional-photo-overlay-' + slot);
        const text = document.getElementById('additional-photo-text-' + slot);
        const icon = overlay.querySelector('i');
        const input = document.getElementById('additional-photo-input-' + slot);
        const removeBtn = document.getElementById('additional-photo-remove-btn-' + slot);

        // Reset input
        input.value = '';

        // Hide preview
        previewImg.src = '#';
        previewImg.classList.add('d-none');

        // Reset overlay
        overlay.style.background = 'rgba(0,0,0,0.02)';
        overlay.style.color = '#9ca3af';
        text.textContent = 'Upload Foto ' + slot;
        if (icon) {
            icon.className = 'fas fa-plus mb-2';
        }

        // Hide remove button
        if (removeBtn) {
            removeBtn.classList.add('d-none');
        }
    }
</script>
@endpush
