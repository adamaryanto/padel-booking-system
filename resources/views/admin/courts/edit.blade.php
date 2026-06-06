@extends('layouts.admin')

@section('title', 'Edit Lapangan')
@section('header', 'Edit Lapangan')

@section('content')
<div class="mb-4 mt-n2">
    <p class="text-muted text-sm">Ubah detail, harga operasional, dan status untuk lapangan {{ $court->name }}.</p>
</div>

@php
    // Extract location from description
    $location = '';
    if (stripos($court->description, 'Jakarta Selatan') !== false) {
        $location = 'Jakarta Selatan';
    } elseif (stripos($court->description, 'Jakarta Barat') !== false) {
        $location = 'Jakarta Barat';
    } elseif (stripos($court->description, 'Tangerang') !== false) {
        $location = 'Tangerang';
    } elseif (stripos($court->description, 'Bekasi') !== false) {
        $location = 'Bekasi';
    } else {
        if (preg_match('/Lokasi:\s*([^.]+)\./i', $court->description, $matches)) {
            $location = trim($matches[1]);
        }
    }
    
    // Extract court type
    $courtType = 'Outdoor';
    if (stripos($court->facilities, 'indoor') !== false || stripos($court->name, 'indoor') !== false || stripos($court->description, 'indoor') !== false) {
        $courtType = 'Indoor';
    }
    
    // Clean description and facilities for editing
    $cleanDesc = preg_replace('/Lokasi:\s*[^.]+\.\s*/i', '', $court->description ?? '');
    $cleanFacilities = preg_replace('/(Indoor|Outdoor)\s+Court,\s*/i', '', $court->facilities ?? '');
@endphp

<div class="row">
    <div class="col-lg-9 col-xl-8">
        <form action="{{ route('admin.courts.update', $court) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <!-- Basic Information Card -->
            <div class="card border-gray-200 bg-white mb-4" style="border-radius: 1.5rem !important;">
                <div class="card-header border-0 bg-transparent pt-4 px-4 pb-0">
                    <h5 class="font-weight-bold text-dark mb-0">Informasi Dasar</h5>
                </div>
                <div class="card-body p-4">
                    <!-- Court Name -->
                    <div class="form-group mb-4">
                        <label for="name" class="font-weight-bold text-xs uppercase tracking-wider text-muted mb-2 d-block">Nama Lapangan <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control rounded-xl @error('name') is-invalid @enderror" placeholder="Contoh: Elite Padel Arena" value="{{ old('name', $court->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Location -->
                    <div class="form-group mb-4">
                        <label for="location" class="font-weight-bold text-xs uppercase tracking-wider text-muted mb-2 d-block">Lokasi <span class="text-danger">*</span></label>
                        <input type="text" name="location" id="location" class="form-control rounded-xl @error('location') is-invalid @enderror" placeholder="Contoh: Jakarta Selatan" value="{{ old('location', $location) }}" required>
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="form-group mb-4">
                        <label for="description" class="font-weight-bold text-xs uppercase tracking-wider text-muted mb-2 d-block">Deskripsi</label>
                        <textarea name="description" id="description" rows="4" class="form-control rounded-xl @error('description') is-invalid @enderror" placeholder="Ceritakan detail lapangan, keunikan, atau informasi penting lainnya...">{{ old('description', $cleanDesc) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Facilities -->
                    <div class="form-group mb-0">
                        <label for="facilities" class="font-weight-bold text-xs uppercase tracking-wider text-muted mb-2 d-block">Fasilitas Tambahan</label>
                        <textarea name="facilities" id="facilities" rows="2" class="form-control rounded-xl @error('facilities') is-invalid @enderror" placeholder="Contoh: Ruang Ganti, Area Parkir, Kafe & Lounge, Shower Area (pisahkan dengan koma)">{{ old('facilities', $cleanFacilities) }}</textarea>
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
                                    <input type="number" name="price_per_hour" id="price_per_hour" class="form-control rounded-xl border-left-0 @error('price_per_hour') is-invalid @enderror" placeholder="150000" value="{{ old('price_per_hour', $court->price_per_hour) }}">
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
                                    <input type="number" name="price_weekday" id="price_weekday" class="form-control rounded-xl border-left-0 @error('price_weekday') is-invalid @enderror" placeholder="150000" value="{{ old('price_weekday', $court->price_weekday) }}" required>
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
                                    <input type="number" name="price_weekend" id="price_weekend" class="form-control rounded-xl border-left-0 @error('price_weekend') is-invalid @enderror" placeholder="175000" value="{{ old('price_weekend', $court->price_weekend) }}" required>
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
                                <input type="time" name="open_time" id="open_time" class="form-control rounded-xl @error('open_time') is-invalid @enderror" value="{{ old('open_time', $court->open_time ? \Carbon\Carbon::parse($court->open_time)->format('H:i') : '06:00') }}">
                                @error('open_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Close Time -->
                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label for="close_time" class="font-weight-bold text-xs uppercase tracking-wider text-muted mb-2 d-block">Jam Tutup</label>
                                <input type="time" name="close_time" id="close_time" class="form-control rounded-xl @error('close_time') is-invalid @enderror" value="{{ old('close_time', $court->close_time ? \Carbon\Carbon::parse($court->close_time)->format('H:i') : '22:00') }}">
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
                                    <option value="Indoor" {{ old('court_type', $courtType) == 'Indoor' ? 'selected' : '' }}>Indoor</option>
                                    <option value="Outdoor" {{ old('court_type', $courtType) == 'Outdoor' ? 'selected' : '' }}>Outdoor</option>
                                </select>
                            </div>
                        </div>

                        <!-- Court Status -->
                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label for="is_active" class="font-weight-bold text-xs uppercase tracking-wider text-muted mb-2 d-block">Court Status</label>
                                <select name="is_active" id="is_active" class="form-control rounded-xl custom-select">
                                    <option value="1" {{ old('is_active', $court->is_active) == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('is_active', $court->is_active) == '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Member Promo -->
            <div class="card border-gray-200 bg-white mb-4" style="border-radius: 1.5rem !important;">
                <div class="card-header border-0 bg-transparent pt-4 px-4 pb-0">
                    <h5 class="font-weight-bold text-dark mb-0">Promo Member</h5>
                </div>
                <div class="card-body p-4">
                    <div class="form-group mb-0">
                        <label for="member_promo" class="font-weight-bold text-xs uppercase tracking-wider text-muted mb-2 d-block">Promo Khusus Member</label>
                        <input type="text" name="member_promo" id="member_promo" class="form-control rounded-xl @error('member_promo') is-invalid @enderror" placeholder="Contoh: Diskon 10% untuk Silver Member" value="{{ old('member_promo', $court->member_promo) }}">
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
                        <label for="photo" class="font-weight-bold text-xs uppercase tracking-wider text-muted mb-2 d-block">Update Foto Utama</label>
                        <div class="row align-items-center">
                            @if($court->photo)
                            <div class="col-md-3 mb-3 mb-md-0">
                                <div class="bg-light rounded-xl border p-1 d-flex align-items-center justify-content-center" style="height: 100px; overflow: hidden;">
                                    <img src="{{ asset('storage/' . $court->photo) }}" alt="{{ $court->name }}" class="img-fluid rounded" style="max-height: 100%; object-fit: cover;">
                                </div>
                            </div>
                            @endif
                            <div class="{{ $court->photo ? 'col-md-9' : 'col-md-12' }}">
                                <div class="custom-file">
                                    <input type="file" name="photo" class="custom-file-input" id="photo" accept="image/*">
                                    <label class="custom-file-label rounded-xl" for="photo">Ganti Foto Utama...</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Photos -->
                    <div class="form-group mb-0">
                        <label class="font-weight-bold text-xs uppercase tracking-wider text-muted mb-3 d-block">Foto Tambahan (Maksimal 3)</label>
                        <div class="row">
                            @for($i = 1; $i <= 3; $i++)
                                @php
                                    $img = isset($court->images[$i-1]) ? $court->images[$i-1] : null;
                                @endphp
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <div class="card h-100 border-2 border-dashed rounded-xl position-relative overflow-hidden additional-photo-box bg-light" 
                                         id="additional-photo-box-{{ $i }}" 
                                         style="min-height: 140px; cursor: pointer; border-style: dashed !important; border-width: 2px !important; border-color: #e5e7eb !important;"
                                         onclick="triggerFileInput({{ $i }})">
                                        
                                        <!-- Image Preview -->
                                        <img id="additional-photo-preview-{{ $i }}" 
                                             src="{{ $img ? asset('storage/' . $img->path) : '#' }}" 
                                             alt="Foto {{ $i }}" 
                                             class="img-fluid w-100 h-100 {{ $img ? '' : 'd-none' }}" 
                                             style="object-fit: cover; position: absolute; top: 0; left: 0;">
                                        
                                        <!-- Overlay / Placeholder -->
                                        <div id="additional-photo-overlay-{{ $i }}" 
                                             class="w-100 h-100 position-absolute d-flex flex-column align-items-center justify-content-center {{ $img ? 'photo-overlay d-none' : '' }}" 
                                             style="top: 0; left: 0; background: rgba(0,0,0,0.02); color: #9ca3af; transition: background 0.3s;">
                                            <i class="fas {{ $img ? 'fa-edit' : 'fa-plus' }} mb-2" style="font-size: 1.25rem;"></i>
                                            <span class="small font-weight-bold text-uppercase tracking-wider" id="additional-photo-text-{{ $i }}" style="font-size: 0.65rem;">
                                                {{ $img ? 'Ganti Foto' : 'Upload Foto ' . $i }}
                                            </span>
                                        </div>

                                        <!-- Delete Button -->
                                        @if($img)
                                            <button type="button" 
                                                    class="btn btn-danger btn-sm position-absolute rounded-circle shadow-sm" 
                                                    style="top: 8px; right: 8px; z-index: 10; width: 28px; height: 28px; padding: 0; display: flex; align-items: center; justify-content: center;" 
                                                    onclick="removePhoto(event, {{ $i }}, {{ $img->id }})">
                                                <i class="fas fa-trash-alt text-white" style="font-size: 0.75rem;"></i>
                                            </button>
                                        @else
                                            <button type="button" 
                                                    class="btn btn-danger btn-sm position-absolute rounded-circle shadow-sm d-none" 
                                                    style="top: 8px; right: 8px; z-index: 10; width: 28px; height: 28px; padding: 0; display: flex; align-items: center; justify-content: center;" 
                                                    id="additional-photo-remove-btn-{{ $i }}"
                                                    onclick="removePhoto(event, {{ $i }})">
                                                <i class="fas fa-trash-alt text-white" style="font-size: 0.75rem;"></i>
                                            </button>
                                        @endif
                                    </div>
                                    
                                    <!-- Hidden inputs -->
                                    <input type="file" 
                                           name="additional_photo_{{ $i }}" 
                                           id="additional-photo-input-{{ $i }}" 
                                           class="d-none" 
                                           accept="image/*" 
                                           onchange="previewAdditionalPhoto(this, {{ $i }})">
                                           
                                    <input type="hidden" 
                                           name="remove_additional_photo_{{ $i }}" 
                                           id="remove-additional-photo-{{ $i }}" 
                                           value="0">
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="d-flex justify-content-between align-items-center mb-5">
                <a href="{{ route('admin.courts.index') }}" class="text-muted font-weight-bold text-sm">
                    Batal Ubah
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
    .additional-photo-box:hover .photo-overlay {
        display: flex !important;
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
                } else {
                    const card = document.getElementById('additional-photo-box-' + slot);
                    const btn = card.querySelector('button');
                    if (btn) btn.classList.remove('d-none');
                }

                // Mark as not removed
                document.getElementById('remove-additional-photo-' + slot).value = '0';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removePhoto(event, slot, imageId = null) {
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
        } else {
            const card = document.getElementById('additional-photo-box-' + slot);
            const btn = card.querySelector('button');
            if (btn) btn.classList.add('d-none');
        }

        // Mark as removed
        if (imageId) {
            document.getElementById('remove-additional-photo-' + slot).value = imageId;
        } else {
            document.getElementById('remove-additional-photo-' + slot).value = '1';
        }
    }
</script>
@endpush
