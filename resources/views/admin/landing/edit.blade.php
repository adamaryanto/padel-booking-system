@extends('layouts.admin')

@section('title', 'Landing Page Editor')
@section('header', 'Landing Page Editor')

@section('content')
<!-- Page header info -->
<div class="mb-4 mt-n2 d-flex justify-content-between align-items-center flex-wrap gap-3">
    <p class="text-muted text-sm mb-0">Sesuaikan dan kelola seluruh konten tampilan depan (Landing Page) website PadelHub secara real-time pada halaman ini.</p>
    
    <!-- Quick Publication Status Widget -->
    <div class="d-flex align-items-center flex-wrap gap-2">
        <span class="text-xs font-weight-bold text-muted">Status:</span>
        <span class="badge {{ $status === 'published' ? 'badge-success' : 'badge-warning' }}" style="font-size: 0.75rem !important;">
            {{ strtoupper($status) }}
        </span>
        <span class="text-xs text-muted font-weight-semibold mr-2">Update: {{ $lastUpdated }}</span>
        
        <a href="{{ route('storage.link') }}" class="btn btn-xs btn-outline-primary py-1 px-2.5 rounded-lg font-weight-bold text-xxs d-inline-flex align-items-center" title="Hubungkan folder publik dengan folder penyimpanan agar gambar muncul di cPanel.">
            <i class="fas fa-link mr-1"></i> Generate Storage Link
        </a>
    </div>
</div>

<div class="row">
    <!-- Full Width Editor Content (No Sidebar) -->
    <div class="col-12">
        <form action="{{ route('admin.landing.update') }}" method="POST" enctype="multipart/form-data" id="cms-editor-form">
            @csrf
            @method('PUT')

            <!-- 1. HERO SECTION -->
            <div class="card border-gray-200 bg-white p-4 mb-4 shadow-sm" style="border-radius: 1.25rem !important;">
                <div class="card-header border-0 bg-transparent p-0 mb-4">
                    <h5 class="font-weight-extrabold text-dark mb-1"><i class="fas fa-rocket mr-2 text-primary"></i> Hero Section Editor</h5>
                    <p class="text-muted text-xs mb-0">Ubah teks utama, deskripsi, gambar latar belakang, dan tombol aksi di bagian paling atas landing page.</p>
                </div>
                <div class="card-body p-0">
                    <div class="form-group mb-4">
                        <label for="hero_title" class="font-weight-bold text-xxs uppercase tracking-wider text-muted mb-2 d-block">Hero Title</label>
                        <input type="text" name="hero_title" id="hero_title" class="form-control rounded-xl font-weight-bold" placeholder="Main Padel Jadi Lebih Mudah" value="{{ old('hero_title', $content->hero_title) }}" required>
                    </div>
                    <div class="form-group mb-4">
                        <label for="hero_subtitle" class="font-weight-bold text-xxs uppercase tracking-wider text-muted mb-2 d-block">Hero Subtitle</label>
                        <textarea name="hero_subtitle" id="hero_subtitle" rows="3" class="form-control rounded-xl text-sm" placeholder="Cari, booking, dan bayar lapangan padel favoritmu..." style="line-height: 1.5;">{{ old('hero_subtitle', $content->hero_subtitle) }}</textarea>
                    </div>
                    
                    <!-- Buttons Group -->
                    <div class="row">
                        <div class="col-md-6 form-group mb-4">
                            <label for="hero_cta_text" class="font-weight-bold text-xxs uppercase tracking-wider text-muted mb-2 d-block">Primary Button Text</label>
                            <input type="text" name="hero_cta_text" id="hero_cta_text" class="form-control rounded-xl text-sm" placeholder="Booking Sekarang" value="{{ old('hero_cta_text', $content->hero_cta_text) }}">
                        </div>
                        <div class="col-md-6 form-group mb-4">
                            <label for="hero_cta_link" class="font-weight-bold text-xxs uppercase tracking-wider text-muted mb-2 d-block">Primary Button Link</label>
                            <input type="text" name="hero_cta_link" id="hero_cta_link" class="form-control rounded-xl text-sm" placeholder="#courts" value="{{ old('hero_cta_link', $content->hero_cta_link) }}">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 form-group mb-4">
                            <label for="hero_secondary_text" class="font-weight-bold text-xxs uppercase tracking-wider text-muted mb-2 d-block">Secondary Button Text</label>
                            <input type="text" name="hero_secondary_text" id="hero_secondary_text" class="form-control rounded-xl text-sm" placeholder="Lihat Lapangan" value="{{ old('hero_secondary_text', $extras['hero_secondary_text'] ?? 'Lihat Lapangan') }}">
                        </div>
                        <div class="col-md-6 form-group mb-4">
                            <label for="hero_secondary_link" class="font-weight-bold text-xxs uppercase tracking-wider text-muted mb-2 d-block">Secondary Button Link</label>
                            <input type="text" name="hero_secondary_link" id="hero_secondary_link" class="form-control rounded-xl text-sm" placeholder="#courts" value="{{ old('hero_secondary_link', $extras['hero_secondary_link'] ?? '#courts') }}">
                        </div>
                    </div>
                    
                    <!-- Images & Status -->
                    <div class="row">
                        <!-- Hero Image -->
                        <div class="col-md-6 form-group mb-4">
                            <label class="font-weight-bold text-xxs uppercase tracking-wider text-muted mb-2 d-block">Hero Image Overlay</label>
                            <div class="image-preview-upload-box rounded-2xl position-relative d-flex align-items-center justify-content-center overflow-hidden" 
                                 style="height: 140px;"
                                 onclick="document.getElementById('hero_image').click()">
                                
                                <input type="file" name="hero_image" id="hero_image" class="d-none image-upload-input" accept="image/*" data-preview-target="hero-preview-img" data-placeholder-target="hero-placeholder">
                                
                                <div id="hero-preview-img" class="w-100 h-100 {{ $content->hero_image ? '' : 'd-none' }}">
                                    <img src="{{ $content->hero_image ? (str_starts_with($content->hero_image, 'http') ? $content->hero_image : url('storage/' . $content->hero_image)) : '' }}" 
                                         class="w-100 h-100" style="object-fit: cover;">
                                    <div class="upload-hover-overlay position-absolute w-100 h-100 d-flex align-items-center justify-content-center text-white" 
                                         style="top: 0; left: 0; opacity: 0; transition: opacity 0.2s;">
                                        <span class="text-xs font-weight-bold"><i class="fas fa-edit mr-1"></i> Ubah Gambar</span>
                                    </div>
                                </div>
                                
                                <div id="hero-placeholder" class="text-center p-3 {{ $content->hero_image ? 'd-none' : '' }}">
                                    <i class="fas fa-cloud-upload-alt text-muted fa-2x mb-2"></i>
                                    <span class="d-block text-xs font-weight-bold text-dark">Klik untuk Upload Hero Image</span>
                                    <span class="d-block text-xxs text-muted mt-1">Format: JPG, PNG, WebP (Max. 2MB)</span>
                                </div>
                            </div>
                        </div>

                        <!-- Background Image -->
                        <div class="col-md-6 form-group mb-4">
                            <label class="font-weight-bold text-xxs uppercase tracking-wider text-muted mb-2 d-block">Hero Background Image</label>
                            <div class="image-preview-upload-box rounded-2xl position-relative d-flex align-items-center justify-content-center overflow-hidden" 
                                 style="height: 140px;"
                                 onclick="document.getElementById('hero_bg_image').click()">
                                
                                <input type="file" name="hero_bg_image" id="hero_bg_image" class="d-none image-upload-input" accept="image/*" data-preview-target="hero-bg-preview-img" data-placeholder-target="hero-bg-placeholder">
                                
                                <div id="hero-bg-preview-img" class="w-100 h-100 {{ ($extras['hero_bg_image'] ?? null) ? '' : 'd-none' }}">
                                    <img src="{{ ($extras['hero_bg_image'] ?? null) ? url('storage/' . $extras['hero_bg_image']) : '' }}" 
                                         class="w-100 h-100" style="object-fit: cover;">
                                    <div class="upload-hover-overlay position-absolute w-100 h-100 d-flex align-items-center justify-content-center text-white" 
                                         style="top: 0; left: 0; opacity: 0; transition: opacity 0.2s;">
                                        <span class="text-xs font-weight-bold"><i class="fas fa-edit mr-1"></i> Ubah Background</span>
                                    </div>
                                </div>
                                
                                <div id="hero-bg-placeholder" class="text-center p-3 {{ ($extras['hero_bg_image'] ?? null) ? 'd-none' : '' }}">
                                    <i class="fas fa-cloud-upload-alt text-muted fa-2x mb-2"></i>
                                    <span class="d-block text-xs font-weight-bold text-dark">Klik untuk Upload Background</span>
                                    <span class="d-block text-xxs text-muted mt-1">Format: JPG, PNG, WebP (Max. 2MB)</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-0">
                        <label for="hero_status" class="font-weight-bold text-xxs uppercase tracking-wider text-muted mb-2 d-block">Hero Status</label>
                        <select name="hero_status" id="hero_status" class="form-control rounded-xl custom-select">
                            <option value="active" {{ ($extras['hero_status'] ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="hidden" {{ ($extras['hero_status'] ?? '') === 'hidden' ? 'selected' : '' }}>Hidden</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- 2. STATISTICS SECTION -->
            <div class="card border-gray-200 bg-white p-4 mb-4 shadow-sm" style="border-radius: 1.25rem !important;">
                <div class="card-header border-0 bg-transparent p-0 mb-4">
                    <h5 class="font-weight-extrabold text-dark mb-1"><i class="fas fa-chart-line mr-2 text-primary"></i> Statistics Section Editor</h5>
                    <p class="text-muted text-xs mb-0">Ubah data statistik utama yang dipajang di landing page.</p>
                </div>
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-md-6 form-group mb-4">
                            <label for="stat_courts" class="font-weight-bold text-xxs uppercase tracking-wider text-muted mb-2 d-block">Total Courts</label>
                            <input type="text" name="stat_courts" id="stat_courts" class="form-control rounded-xl text-sm" placeholder="500+" value="{{ old('stat_courts', $extras['stat_courts'] ?? '500+') }}">
                        </div>
                        <div class="col-md-6 form-group mb-4">
                            <label for="stat_cities" class="font-weight-bold text-xxs uppercase tracking-wider text-muted mb-2 d-block">Total Cities</label>
                            <input type="text" name="stat_cities" id="stat_cities" class="form-control rounded-xl text-sm" placeholder="20+" value="{{ old('stat_cities', $extras['stat_cities'] ?? '20+') }}">
                        </div>
                        <div class="col-md-6 form-group mb-4">
                            <label for="stat_members" class="font-weight-bold text-xxs uppercase tracking-wider text-muted mb-2 d-block">Total Active Members</label>
                            <input type="text" name="stat_members" id="stat_members" class="form-control rounded-xl text-sm" placeholder="10.000+" value="{{ old('stat_members', $extras['stat_members'] ?? '10.000+') }}">
                        </div>
                        <div class="col-md-6 form-group mb-4">
                            <label for="stat_bookings" class="font-weight-bold text-xxs uppercase tracking-wider text-muted mb-2 d-block">Total Successful Bookings</label>
                            <input type="text" name="stat_bookings" id="stat_bookings" class="form-control rounded-xl text-sm" placeholder="50.000+" value="{{ old('stat_bookings', $extras['stat_bookings'] ?? '50.000+') }}">
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. FEATURES SECTION -->
            <div class="card border-gray-200 bg-white p-4 mb-4 shadow-sm" style="border-radius: 1.25rem !important;">
                <div class="card-header border-0 bg-transparent p-0 mb-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="font-weight-extrabold text-dark mb-1"><i class="fas fa-star mr-2 text-primary"></i> Features Section Editor</h5>
                        <p class="text-muted text-xs mb-0">Kelola dan urutkan daftar fitur unggulan PadelHub.</p>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm rounded-xl px-3 font-weight-bold text-xs" onclick="addFeatureRow()">
                        <i class="fas fa-plus mr-1"></i> Add Feature
                    </button>
                </div>
                <div class="card-body p-0">
                    <div id="features-list-container">
                        <!-- Feature items dynamically loaded -->
                    </div>
                </div>
            </div>

            <!-- 4. POPULAR COURTS SECTION -->
            <div class="card border-gray-200 bg-white p-4 mb-4 shadow-sm" style="border-radius: 1.25rem !important;">
                <div class="card-header border-0 bg-transparent p-0 mb-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="font-weight-extrabold text-dark mb-1"><i class="fas fa-layer-group mr-2 text-primary"></i> Popular Courts Editor</h5>
                        <p class="text-muted text-xs mb-0">Kelola daftar lapangan populer yang direkomendasikan di landing page.</p>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm rounded-xl px-3 font-weight-bold text-xs" onclick="addCourtRow()">
                        <i class="fas fa-plus mr-1"></i> Add Court Card
                    </button>
                </div>
                <div class="card-body p-0">
                    <div id="courts-list-container">
                        <!-- Popular Court items dynamically loaded -->
                    </div>
                </div>
            </div>

            <!-- 5. TESTIMONIALS SECTION -->
            <div class="card border-gray-200 bg-white p-4 mb-4 shadow-sm" style="border-radius: 1.25rem !important;">
                <div class="card-header border-0 bg-transparent p-0 mb-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="font-weight-extrabold text-dark mb-1"><i class="fas fa-comments mr-2 text-primary"></i> Testimonials Editor</h5>
                        <p class="text-muted text-xs mb-0">Kelola ulasan, foto, dan penilaian kepuasan dari pelanggan.</p>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm rounded-xl px-3 font-weight-bold text-xs" onclick="addTestimonialRow()">
                        <i class="fas fa-plus mr-1"></i> Add Testimonial
                    </button>
                </div>
                <div class="card-body p-0">
                    <div id="testimonials-list-container">
                        <!-- Testimonial items dynamically loaded -->
                    </div>
                </div>
            </div>

            <!-- 6. MEMBERSHIP SECTION -->
            <div class="card border-gray-200 bg-white p-4 mb-4 shadow-sm" style="border-radius: 1.25rem !important;">
                <div class="card-header border-0 bg-transparent p-0 mb-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="font-weight-extrabold text-dark mb-1"><i class="fas fa-credit-card mr-2 text-primary"></i> Membership Plans Editor</h5>
                        <p class="text-muted text-xs mb-0">Kelola visual paket keanggotaan/membership yang dipajang untuk pelanggan.</p>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm rounded-xl px-3 font-weight-bold text-xs" onclick="addMembershipRow()">
                        <i class="fas fa-plus mr-1"></i> Add Package Card
                    </button>
                </div>
                <div class="card-body p-0">
                    <div id="membership-list-container">
                        <!-- Membership items dynamically loaded -->
                    </div>
                </div>
            </div>

            <!-- 7. CTA SECTION -->
            <div class="card border-gray-200 bg-white p-4 mb-4 shadow-sm" style="border-radius: 1.25rem !important;">
                <div class="card-header border-0 bg-transparent p-0 mb-4">
                    <h5 class="font-weight-extrabold text-dark mb-1"><i class="fas fa-bullhorn mr-2 text-primary"></i> Call To Action (CTA) Editor</h5>
                    <p class="text-muted text-xs mb-0">Kelola teks ajakan bertindak, background, dan tombol di bagian bawah landing page.</p>
                </div>
                <div class="card-body p-0">
                    <div class="form-group mb-4">
                        <label for="cta_title" class="font-weight-bold text-xxs uppercase tracking-wider text-muted mb-2 d-block">CTA Title</label>
                        <input type="text" name="cta_title" id="cta_title" class="form-control rounded-xl font-weight-bold" placeholder="Siap Bermain Hari Ini?" value="{{ old('cta_title', $extras['cta_title'] ?? 'Siap Bermain Hari Ini?') }}">
                    </div>
                    <div class="form-group mb-4">
                        <label for="cta_subtitle" class="font-weight-bold text-xxs uppercase tracking-wider text-muted mb-2 d-block">CTA Description</label>
                        <textarea name="cta_subtitle" id="cta_subtitle" rows="3" class="form-control rounded-xl text-sm" placeholder="Temukan lapangan padel terbaik di kotamu..." style="line-height: 1.5;">{{ old('cta_subtitle', $extras['cta_subtitle'] ?? 'Temukan lapangan padel terbaik di kotamu dan lakukan booking dalam hitungan menit.') }}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group mb-4">
                            <label for="cta_btn_text" class="font-weight-bold text-xxs uppercase tracking-wider text-muted mb-2 d-block">CTA Button Text</label>
                            <input type="text" name="cta_btn_text" id="cta_btn_text" class="form-control rounded-xl text-sm" placeholder="Booking Sekarang" value="{{ old('cta_btn_text', $extras['cta_btn_text'] ?? 'Booking Sekarang') }}">
                        </div>
                        <div class="col-md-6 form-group mb-4">
                            <label for="cta_btn_link" class="font-weight-bold text-xxs uppercase tracking-wider text-muted mb-2 d-block">CTA Button Link</label>
                            <input type="text" name="cta_btn_link" id="cta_btn_link" class="form-control rounded-xl text-sm" placeholder="#courts" value="{{ old('cta_btn_link', $extras['cta_btn_link'] ?? '#courts') }}">
                        </div>
                    </div>
                    
                    <!-- Background Image & Status -->
                    <div class="row">
                        <div class="col-md-6 form-group mb-4">
                            <label class="font-weight-bold text-xxs uppercase tracking-wider text-muted mb-2 d-block">CTA Background Image</label>
                            <div class="image-preview-upload-box rounded-2xl position-relative d-flex align-items-center justify-content-center overflow-hidden" 
                                 style="height: 140px;"
                                 onclick="document.getElementById('cta_bg_image').click()">
                                
                                <input type="file" name="cta_bg_image" id="cta_bg_image" class="d-none image-upload-input" accept="image/*" data-preview-target="cta-bg-preview-img" data-placeholder-target="cta-bg-placeholder">
                                
                                <div id="cta-bg-preview-img" class="w-100 h-100 {{ ($extras['cta_bg_image'] ?? null) ? '' : 'd-none' }}">
                                    <img src="{{ ($extras['cta_bg_image'] ?? null) ? url('storage/' . $extras['cta_bg_image']) : '' }}" 
                                         class="w-100 h-100" style="object-fit: cover;">
                                    <div class="upload-hover-overlay position-absolute w-100 h-100 d-flex align-items-center justify-content-center text-white" 
                                         style="top: 0; left: 0; opacity: 0; transition: opacity 0.2s;">
                                        <span class="text-xs font-weight-bold"><i class="fas fa-edit mr-1"></i> Ubah Background</span>
                                    </div>
                                </div>
                                
                                <div id="cta-bg-placeholder" class="text-center p-3 {{ ($extras['cta_bg_image'] ?? null) ? 'd-none' : '' }}">
                                    <i class="fas fa-cloud-upload-alt text-muted fa-2x mb-2"></i>
                                    <span class="d-block text-xs font-weight-bold text-dark">Klik untuk Upload Background</span>
                                    <span class="d-block text-xxs text-muted mt-1">Format: JPG, PNG, WebP (Max. 2MB)</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 form-group mb-4">
                            <label for="cta_status" class="font-weight-bold text-xxs uppercase tracking-wider text-muted mb-2 d-block">CTA Status</label>
                            <select name="cta_status" id="cta_status" class="form-control rounded-xl custom-select">
                                <option value="active" {{ ($extras['cta_status'] ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="hidden" {{ ($extras['cta_status'] ?? '') === 'hidden' ? 'selected' : '' }}>Hidden</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 8. FOOTER SECTION -->
            <div class="card border-gray-200 bg-white p-4 mb-4 shadow-sm" style="border-radius: 1.25rem !important;">
                <div class="card-header border-0 bg-transparent p-0 mb-4">
                    <h5 class="font-weight-extrabold text-dark mb-1"><i class="fas fa-info-circle mr-2 text-primary"></i> Company Contact & Footer Settings</h5>
                    <p class="text-muted text-xs mb-0">Ubah kontak fisik, tautan sosial media, identitas logo/favicon, dan teks copyright.</p>
                </div>
                <div class="card-body p-0">
                    
                    <!-- Company Info -->
                    <h6 class="font-weight-bold text-muted uppercase tracking-wider text-xxs mb-3">Company Information</h6>
                    <div class="row">
                        <div class="col-md-6 form-group mb-4">
                            <label for="company_name" class="font-weight-bold text-xxs uppercase tracking-wider text-muted mb-2 d-block">Company Name</label>
                            <input type="text" name="company_name" id="company_name" class="form-control rounded-xl text-sm" placeholder="PadelHub Indonesia" value="{{ old('company_name', $extras['company_name'] ?? 'PadelHub') }}">
                        </div>
                        <div class="col-md-6 form-group mb-4">
                            <label for="contact_email" class="font-weight-bold text-xxs uppercase tracking-wider text-muted mb-2 d-block">Email Address</label>
                            <input type="email" name="contact_email" id="contact_email" class="form-control rounded-xl text-sm" placeholder="hello@padelhub.com" value="{{ old('contact_email', $content->contact_email) }}">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 form-group mb-4">
                            <label for="contact_phone" class="font-weight-bold text-xxs uppercase tracking-wider text-muted mb-2 d-block">Phone Number</label>
                            <input type="text" name="contact_phone" id="contact_phone" class="form-control rounded-xl text-sm" placeholder="+62 812-3456-7890" value="{{ old('contact_phone', $content->contact_phone) }}">
                        </div>
                        <div class="col-md-6 form-group mb-4">
                            <label for="whatsapp_number" class="font-weight-bold text-xxs uppercase tracking-wider text-muted mb-2 d-block">WhatsApp Number</label>
                            <input type="text" name="whatsapp_number" id="whatsapp_number" class="form-control rounded-xl text-sm" placeholder="6281234567890" value="{{ old('whatsapp_number', $content->whatsapp_number) }}">
                        </div>
                    </div>
                    
                    <div class="form-group mb-5">
                        <label for="contact_address" class="font-weight-bold text-xxs uppercase tracking-wider text-muted mb-2 d-block">Physical Address</label>
                        <textarea name="contact_address" id="contact_address" rows="3" class="form-control rounded-xl text-sm" placeholder="Jl. Jenderal Sudirman No. 12, Jakarta Barat">{{ old('contact_address', $content->contact_address) }}</textarea>
                    </div>

                    <hr class="my-4">

                    <!-- Social Media Links -->
                    <h6 class="font-weight-bold text-muted uppercase tracking-wider text-xxs mb-3">Social Media URLs</h6>
                    <div class="row">
                        <div class="col-md-6 form-group mb-4">
                            <label for="social_instagram" class="font-weight-bold text-xxs uppercase tracking-wider text-muted mb-2 d-block">Instagram Link</label>
                            <input type="text" name="social_instagram" id="social_instagram" class="form-control rounded-xl text-sm" placeholder="https://instagram.com/padelhub" value="{{ old('social_instagram', $extras['social_instagram'] ?? '') }}">
                        </div>
                        <div class="col-md-6 form-group mb-4">
                            <label for="social_facebook" class="font-weight-bold text-xxs uppercase tracking-wider text-muted mb-2 d-block">Facebook Link</label>
                            <input type="text" name="social_facebook" id="social_facebook" class="form-control rounded-xl text-sm" placeholder="https://facebook.com/padelhub" value="{{ old('social_facebook', $extras['social_facebook'] ?? '') }}">
                        </div>
                        <div class="col-md-6 form-group mb-4">
                            <label for="social_tiktok" class="font-weight-bold text-xxs uppercase tracking-wider text-muted mb-2 d-block">TikTok Link</label>
                            <input type="text" name="social_tiktok" id="social_tiktok" class="form-control rounded-xl text-sm" placeholder="https://tiktok.com/@padelhub" value="{{ old('social_tiktok', $extras['social_tiktok'] ?? '') }}">
                        </div>
                        <div class="col-md-6 form-group mb-4">
                            <label for="social_whatsapp" class="font-weight-bold text-xxs uppercase tracking-wider text-muted mb-2 d-block">WhatsApp Chat URL</label>
                            <input type="text" name="social_whatsapp" id="social_whatsapp" class="form-control rounded-xl text-sm" placeholder="https://wa.me/6281234567890" value="{{ old('social_whatsapp', $extras['social_whatsapp'] ?? '') }}">
                        </div>
                        <div class="col-md-6 form-group mb-4">
                            <label for="social_youtube" class="font-weight-bold text-xxs uppercase tracking-wider text-muted mb-2 d-block">YouTube Channel URL</label>
                            <input type="text" name="social_youtube" id="social_youtube" class="form-control rounded-xl text-sm" placeholder="https://youtube.com/padelhub" value="{{ old('social_youtube', $extras['social_youtube'] ?? '') }}">
                        </div>
                        <div class="col-md-6 form-group mb-4">
                            <label for="footer_copyright" class="font-weight-bold text-xxs uppercase tracking-wider text-muted mb-2 d-block">Footer Copyright Text</label>
                            <input type="text" name="footer_copyright" id="footer_copyright" class="form-control rounded-xl text-sm" placeholder="&copy; 2026 PadelHub Indonesia. All Rights Reserved." value="{{ old('footer_copyright', $extras['footer_copyright'] ?? '&copy; 2026 PadelHub Indonesia.') }}">
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Logo/Favicon Upload -->
                    <h6 class="font-weight-bold text-muted uppercase tracking-wider text-xxs mb-3">Identity Logo & Favicon</h6>
                    <div class="row">
                        <div class="col-md-6 form-group mb-4">
                            <label class="font-weight-bold text-xxs uppercase tracking-wider text-muted mb-2 d-block">Website Logo</label>
                            <div class="image-preview-upload-box rounded-2xl position-relative d-flex align-items-center justify-content-center overflow-hidden" 
                                 style="height: 140px;"
                                 onclick="document.getElementById('setting_logo').click()">
                                
                                <input type="file" name="setting_logo" id="setting_logo" class="d-none image-upload-input" accept="image/*" data-preview-target="logo-preview-img" data-placeholder-target="logo-placeholder">
                                
                                <div id="logo-preview-img" class="w-100 h-100 {{ ($extras['setting_logo'] ?? null) ? '' : 'd-none' }}">
                                    <img src="{{ ($extras['setting_logo'] ?? null) ? url('storage/' . $extras['setting_logo']) : '' }}" 
                                         class="w-100 h-100" style="object-fit: contain; padding: 10px;">
                                    <div class="upload-hover-overlay position-absolute w-100 h-100 d-flex align-items-center justify-content-center text-white" 
                                         style="top: 0; left: 0; opacity: 0; transition: opacity 0.2s;">
                                        <span class="text-xs font-weight-bold"><i class="fas fa-edit mr-1"></i> Ubah Logo</span>
                                    </div>
                                </div>
                                
                                <div id="logo-placeholder" class="text-center p-3 {{ ($extras['setting_logo'] ?? null) ? 'd-none' : '' }}">
                                    <i class="fas fa-cloud-upload-alt text-muted fa-2x mb-2"></i>
                                    <span class="d-block text-xs font-weight-bold text-dark">Klik untuk Upload Logo</span>
                                    <span class="d-block text-xxs text-muted mt-1">Format: JPG, PNG, WebP (Max. 2MB)</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 form-group mb-4">
                            <label class="font-weight-bold text-xxs uppercase tracking-wider text-muted mb-2 d-block">Website Favicon</label>
                            <div class="image-preview-upload-box rounded-2xl position-relative d-flex align-items-center justify-content-center overflow-hidden" 
                                 style="height: 140px;"
                                 onclick="document.getElementById('setting_favicon').click()">
                                
                                <input type="file" name="setting_favicon" id="setting_favicon" class="d-none image-upload-input" accept="image/*" data-preview-target="favicon-preview-img" data-placeholder-target="favicon-placeholder">
                                
                                <div id="favicon-preview-img" class="w-100 h-100 {{ ($extras['setting_favicon'] ?? null) ? '' : 'd-none' }}">
                                    <img src="{{ ($extras['setting_favicon'] ?? null) ? url('storage/' . $extras['setting_favicon']) : '' }}" 
                                         class="w-100 h-100" style="object-fit: contain; padding: 25px;">
                                    <div class="upload-hover-overlay position-absolute w-100 h-100 d-flex align-items-center justify-content-center text-white" 
                                         style="top: 0; left: 0; opacity: 0; transition: opacity 0.2s;">
                                        <span class="text-xs font-weight-bold"><i class="fas fa-edit mr-1"></i> Ubah Favicon</span>
                                    </div>
                                </div>
                                
                                <div id="favicon-placeholder" class="text-center p-3 {{ ($extras['setting_favicon'] ?? null) ? 'd-none' : '' }}">
                                    <i class="fas fa-cloud-upload-alt text-muted fa-2x mb-2"></i>
                                    <span class="d-block text-xs font-weight-bold text-dark">Klik untuk Upload Favicon</span>
                                    <span class="d-block text-xxs text-muted mt-1">Format: ICO, PNG (Max. 1MB)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Hidden about values to prevent validation crash -->
                    <input type="hidden" name="about_title" value="{{ $content->about_title ?? 'Fasilitas Premium PadelHub' }}">
                    <input type="hidden" name="about_subtitle" value="{{ $content->about_subtitle ?? 'Mengapa Memilih Kami' }}">
                    <input type="hidden" name="about_description" value="{{ $content->about_description ?? 'Standar elit dalam manajemen lapangan padel.' }}">
                </div>
            </div>

            <!-- 9. LIVE PREVIEW LANDING PAGE -->
            <div class="card border-gray-200 bg-white p-4 mb-4 shadow-sm" style="border-radius: 1.25rem !important;">
                <div class="card-header border-0 bg-transparent p-0 mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h5 class="font-weight-extrabold text-dark mb-1"><i class="fas fa-eye mr-2 text-primary"></i> Live Landing Page Preview</h5>
                        <p class="text-muted text-xs mb-0">Uji visual respon draf Anda secara real-time pada berbagai ukuran layar di bawah ini.</p>
                    </div>
                    
                    <!-- Responsive Width Controllers -->
                    <div class="btn-group btn-group-toggle shadow-sm rounded-xl overflow-hidden" data-toggle="buttons" style="border: 1px solid #e5e7eb;">
                        <label class="btn btn-outline-primary btn-sm py-1.5 px-3 active font-weight-bold" onclick="resizePreview('100%')">
                            <input type="radio" name="preview_mode" checked><i class="fas fa-desktop mr-1.5"></i> Desktop
                        </label>
                        <label class="btn btn-outline-primary btn-sm py-1.5 px-3 font-weight-bold" onclick="resizePreview('768px')">
                            <input type="radio" name="preview_mode"><i class="fas fa-tablet-alt mr-1.5"></i> Tablet
                        </label>
                        <label class="btn btn-outline-primary btn-sm py-1.5 px-3 font-weight-bold" onclick="resizePreview('375px')">
                            <input type="radio" name="preview_mode"><i class="fas fa-mobile-alt mr-1.5"></i> Mobile
                        </label>
                    </div>
                </div>

                <!-- Iframe Container wrapper -->
                <div class="card-body p-0 border rounded-2xl overflow-hidden d-flex justify-content-center bg-dark-card" style="min-height: 550px; border: 1px solid #e5e7eb !important;">
                    <iframe id="landing-preview-frame" src="about:blank" class="w-100 transition-all border-0 shadow-lg" style="height: 600px; max-width: 100%; transition: max-width 0.4s cubic-bezier(0.16, 1, 0.3, 1); background-color: #0f172a;"></iframe>
                </div>
            </div>

            <!-- Sticky Bottom Form Actions (Save Draft only) -->
            <div class="sticky-bottom text-right py-3 bg-light border-top d-flex justify-content-between align-items-center mb-4" style="position: sticky; bottom: 0; z-index: 100; border-top: 1px solid #e5e7eb !important;">
                <span class="text-xs text-muted font-weight-semibold">Simpan draf untuk melihat perubahannya secara instan pada kotak Live Preview di atas.</span>
                <button type="submit" class="btn btn-primary px-5 py-2.5 rounded-xl font-weight-bold shadow-sm d-inline-flex align-items-center">
                    <i class="fas fa-save mr-2"></i> Save Draft
                </button>
            </div>
        </form>

        <!-- Dynamic Separate Publishing Management Control Board -->
        <div class="card border-gray-200 bg-white p-4 mb-5 shadow-sm" style="border-radius: 1.25rem !important;">
            <div class="card-header border-0 bg-transparent p-0 mb-3">
                <h5 class="font-weight-extrabold text-dark mb-1"><i class="fas fa-paper-plane mr-2 text-primary"></i> Publish Management</h5>
                <p class="text-muted text-xs mb-0">Tayangkan draf Anda ke seluruh dunia atau kembalikan ke versi sebelumnya jika terjadi kesalahan.</p>
            </div>
            <div class="card-body p-0 pt-2">
                <div class="row align-items-center">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <div class="p-3 bg-light rounded-xl border border-gray-200">
                            <span class="text-xxs font-weight-bold text-muted uppercase tracking-wider d-block mb-1">Status Publikasi</span>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge {{ $status === 'published' ? 'badge-success' : 'badge-warning' }} px-3 py-1.5" style="font-size: 0.8rem !important; border-radius: 8px !important;">
                                    {{ $status === 'published' ? 'PUBLISHED (LIVE)' : 'DRAFT (UNPUBLISHED)' }}
                                </span>
                            </div>
                            <span class="text-xxs text-muted font-weight-medium d-block mt-2">Versi terakhir diubah pada: <strong>{{ $lastUpdated }}</strong></span>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex flex-wrap gap-2 justify-content-md-end">
                        <!-- Rollback Form -->
                        <form action="{{ route('admin.landing.rollback') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger font-weight-bold rounded-xl px-4 py-2.5 d-inline-flex align-items-center text-xs" onclick="return confirm('Apakah Anda yakin ingin melakukan rollback ke versi live sebelumnya?')">
                                <i class="fas fa-undo mr-1.5 text-xs"></i> Rollback Version
                            </button>
                        </form>

                        <!-- Publish Form -->
                        <form action="{{ route('admin.landing.publish') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="btn btn-success font-weight-bold rounded-xl px-5 py-2.5 d-inline-flex align-items-center text-xs" style="background-color: #059669 !important; border-color: #059669 !important;">
                                <i class="fas fa-paper-plane mr-2 text-xs"></i> Publish Landing Page
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('styles')
<style>
    /* Image Upload Preview Box */
    .image-preview-upload-box {
        border: 2px dashed #d1d5db !important;
        background-color: #f9fafb !important;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        min-height: 140px;
    }
    .image-preview-upload-box:hover {
        border-color: #10b981 !important;
        background-color: #f0fdf4 !important;
    }
    .image-preview-upload-box:hover .upload-hover-overlay {
        opacity: 1 !important;
    }
    .image-preview-upload-box:hover .text-muted, 
    .image-preview-upload-box:hover .text-dark {
        color: #10b981 !important;
    }
    .upload-hover-overlay {
        background: rgba(16, 185, 129, 0.6) !important;
        backdrop-filter: blur(2px);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .list-item-row {
        border: 1px solid #e5e7eb;
        border-radius: 1.25rem;
        background-color: #ffffff;
        transition: border-color 0.2s;
    }
    .list-item-row:hover {
        border-color: #10b981;
    }
    .text-xxs {
        font-size: 0.65rem !important;
        letter-spacing: 0.05em !important;
    }
    .cursor-pointer {
        cursor: pointer;
    }
</style>
@endpush

@push('scripts')
<script>
    // Load pre-existing list items
    let features = {!! json_encode($extras['features'] ?? [
        ['title' => 'Cari Lapangan Cepat', 'desc' => 'Temukan lapangan padel terdekat dalam beberapa detik.', 'icon' => 'fas fa-search', 'status' => 'active'],
        ['title' => 'Sistem Booking Instan', 'desc' => 'Lakukan reservasi jadwal real-time tanpa ribet konfirmasi.', 'icon' => 'fas fa-bolt', 'status' => 'active'],
        ['title' => 'Membership Eksklusif', 'desc' => 'Dapatkan diskon potongan harga khusus bagi pelanggan setia.', 'icon' => 'fas fa-gift', 'status' => 'active']
    ]) !!};

    let testimonials = {!! json_encode($extras['testimonials'] ?? [
        ['name' => 'Budi Santoso', 'review' => 'Aplikasi booking padel terbaik! Transaksi cepat, konfirmasi otomatis.', 'rating' => 5, 'photo' => '', 'status' => 'active'],
        ['name' => 'Amalia Putri', 'review' => 'Sangat praktis digunakan. Rekomendasi sekali buat yang suka main padel.', 'rating' => 4, 'photo' => '', 'status' => 'active']
    ]) !!};

    let popularCourts = {!! json_encode($extras['popular_courts'] ?? [
        ['name' => 'Padel Arena Jakarta', 'location' => 'Jakarta Barat', 'desc' => 'Arena standar panoramic premium dengan pencahayaan malam memukau.', 'price' => 150000, 'image' => '', 'status' => 'active'],
        ['name' => 'Elite Padel Club', 'location' => 'Tangerang', 'desc' => 'Dua lapangan indoor luas dengan karpet pro tur.', 'price' => 180000, 'image' => '', 'status' => 'active']
    ]) !!};

    let membership = {!! json_encode($extras['membership'] ?? [
        ['name' => 'Basic Plan', 'price' => 0, 'duration' => 'Lifetime', 'features' => "- Cari Lapangan\n- Booking Online\n- Riwayat Booking", 'status' => 'active'],
        ['name' => 'Premium Plan', 'price' => 49000, 'duration' => 'Bulan', 'features' => "- Semua Fitur Basic\n- Diskon Booking\n- Prioritas Reservasi\n- Promo Eksklusif", 'status' => 'active']
    ]) !!};

    const storageUrl = "{{ url('storage') }}";

    document.addEventListener('DOMContentLoaded', function() {
        // Handle input files labels display
        $(document).on('change', '.custom-file-input', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);

            // Local preview logic for dynamic list item images
            const file = this.files[0];
            const containerId = this.getAttribute('data-preview-container');
            if (file && containerId) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const container = document.getElementById(containerId);
                    const img = container.querySelector('img');
                    img.src = e.target.result;
                    container.style.setProperty('display', 'flex', 'important');
                }
                reader.readAsDataURL(file);
            }
        });

        // Initialize and render all dynamic lists
        renderAll();

        // Load preview page into iframe automatically
        document.getElementById('landing-preview-frame').src = "{{ url('/') }}?preview=1";

        // Image Preview Upload Helper for Drag & Drop/Clickable upload cards
        document.querySelectorAll('.image-upload-input').forEach(input => {
            input.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    const previewTargetId = this.getAttribute('data-preview-target');
                    const placeholderTargetId = this.getAttribute('data-placeholder-target');
                    
                    reader.onload = function(e) {
                        const imgContainer = document.getElementById(previewTargetId);
                        const imgEl = imgContainer.querySelector('img');
                        const placeholderEl = document.getElementById(placeholderTargetId);
                        
                        imgEl.src = e.target.result;
                        imgContainer.classList.remove('d-none');
                        placeholderEl.classList.add('d-none');
                    }
                    reader.readAsDataURL(file);
                }
            });
        });
    });

    function renderAll() {
        renderFeatures();
        renderPopularCourts();
        renderTestimonials();
        renderMembership();
    }

    // Resize Live Preview iframe helper
    function resizePreview(width) {
        document.getElementById('landing-preview-frame').style.maxWidth = width;
    }

    // --- 1. FEATURES LIST ---
    function renderFeatures() {
        const container = document.getElementById('features-list-container');
        container.innerHTML = '';
        features.forEach((feat, index) => {
            container.appendChild(createFeatureRowHtml(feat, index));
        });
    }

    function createFeatureRowHtml(feat, index) {
        const div = document.createElement('div');
        div.className = 'list-item-row p-3 mb-3';
        div.innerHTML = `
            <div class="row align-items-center">
                <div class="col-md-2 mb-2 mb-md-0">
                    <label class="text-xxs uppercase font-weight-bold text-muted mb-1 d-block">Icon Class</label>
                    <input type="text" name="features[${index}][icon]" class="form-control form-control-sm rounded-lg" value="${feat.icon || 'fas fa-star'}" onchange="features[${index}].icon = this.value">
                </div>
                <div class="col-md-3 mb-2 mb-md-0">
                    <label class="text-xxs uppercase font-weight-bold text-muted mb-1 d-block">Feature Title</label>
                    <input type="text" name="features[${index}][title]" class="form-control form-control-sm rounded-lg font-weight-bold" value="${feat.title || ''}" onchange="features[${index}].title = this.value" required>
                </div>
                <div class="col-md-4 mb-2 mb-md-0">
                    <label class="text-xxs uppercase font-weight-bold text-muted mb-1 d-block">Description</label>
                    <input type="text" name="features[${index}][desc]" class="form-control form-control-sm rounded-lg" value="${feat.desc || ''}" onchange="features[${index}].desc = this.value" required>
                </div>
                <div class="col-md-2 mb-2 mb-md-0">
                    <label class="text-xxs uppercase font-weight-bold text-muted mb-1 d-block">Status</label>
                    <select name="features[${index}][status]" class="form-control form-control-sm rounded-lg custom-select" onchange="features[${index}].status = this.value">
                        <option value="active" ${feat.status === 'active' ? 'selected' : ''}>Active</option>
                        <option value="hidden" ${feat.status === 'hidden' ? 'selected' : ''}>Hidden</option>
                    </select>
                </div>
                <div class="col-md-1 text-right mt-3 mt-md-0 d-flex gap-1 justify-content-end">
                    <button type="button" class="btn btn-outline-secondary btn-xs py-1 px-2" onclick="moveItem('features', ${index}, -1)" title="Pindahkan ke atas"><i class="fas fa-chevron-up text-xxs"></i></button>
                    <button type="button" class="btn btn-outline-secondary btn-xs py-1 px-2" onclick="moveItem('features', ${index}, 1)" title="Pindahkan ke bawah"><i class="fas fa-chevron-down text-xxs"></i></button>
                    <button type="button" class="btn btn-outline-danger btn-xs py-1 px-2" onclick="removeItem('features', ${index})"><i class="fas fa-trash-alt text-xxs"></i></button>
                </div>
            </div>
        `;
        return div;
    }

    function addFeatureRow() {
        features.push({ title: '', desc: '', icon: 'fas fa-star', status: 'active' });
        renderFeatures();
    }


    // --- 2. POPULAR COURTS LIST ---
    function renderPopularCourts() {
        const container = document.getElementById('courts-list-container');
        container.innerHTML = '';
        popularCourts.forEach((court, index) => {
            container.appendChild(createCourtRowHtml(court, index));
        });
    }

    function createCourtRowHtml(court, index) {
        const div = document.createElement('div');
        div.className = 'list-item-row p-3 mb-3';
        div.innerHTML = `
            <input type="hidden" name="popular_courts[${index}][image]" value="${court.image || ''}">
            <div class="row align-items-center">
                <div class="col-md-3 mb-2 mb-md-0">
                    <label class="text-xxs uppercase font-weight-bold text-muted mb-1 d-block">Court Name</label>
                    <input type="text" name="popular_courts[${index}][name]" class="form-control form-control-sm rounded-lg font-weight-bold" value="${court.name || ''}" onchange="popularCourts[${index}].name = this.value" required>
                </div>
                <div class="col-md-2 mb-2 mb-md-0">
                    <label class="text-xxs uppercase font-weight-bold text-muted mb-1 d-block">Location</label>
                    <input type="text" name="popular_courts[${index}][location]" class="form-control form-control-sm rounded-lg" value="${court.location || ''}" onchange="popularCourts[${index}].location = this.value" required>
                </div>
                <div class="col-md-2 mb-2 mb-md-0">
                    <label class="text-xxs uppercase font-weight-bold text-muted mb-1 d-block">Price / Hour</label>
                    <input type="number" name="popular_courts[${index}][price]" class="form-control form-control-sm rounded-lg" value="${court.price || ''}" onchange="popularCourts[${index}].price = this.value" required>
                </div>
                <div class="col-md-2 mb-2 mb-md-0">
                    <label class="text-xxs uppercase font-weight-bold text-muted mb-1 d-block">Status</label>
                    <select name="popular_courts[${index}][status]" class="form-control form-control-sm rounded-lg custom-select" onchange="popularCourts[${index}].status = this.value">
                        <option value="active" ${court.status === 'active' ? 'selected' : ''}>Active</option>
                        <option value="hidden" ${court.status === 'hidden' ? 'selected' : ''}>Hidden</option>
                    </select>
                </div>
                <div class="col-md-2 mb-2 mb-md-0">
                    <label class="text-xxs uppercase font-weight-bold text-muted mb-1 d-block">Upload Court Photo</label>
                    <div id="court_img_preview_container_${index}" class="mb-2 bg-light p-1 border rounded-lg text-center d-flex align-items-center justify-content-center overflow-hidden" style="height: 50px; ${court.image ? '' : 'display: none !important;'}">
                        <img src="${court.image ? storageUrl + '/' + court.image : ''}" class="img-fluid rounded h-100" style="object-fit: cover;">
                    </div>
                    <div class="custom-file custom-file-sm">
                        <input type="file" name="popular_courts[${index}][image_file]" class="custom-file-input" id="popular_courts_img_${index}" data-preview-container="court_img_preview_container_${index}">
                        <label class="custom-file-label rounded-lg form-control-sm" for="popular_courts_img_${index}">Upload...</label>
                    </div>
                </div>
                <div class="col-md-1 text-right mt-3 mt-md-0 d-flex gap-1 justify-content-end">
                    <button type="button" class="btn btn-outline-secondary btn-xs py-1 px-2" onclick="moveItem('popularCourts', ${index}, -1)" title="Ke atas"><i class="fas fa-chevron-up text-xxs"></i></button>
                    <button type="button" class="btn btn-outline-secondary btn-xs py-1 px-2" onclick="moveItem('popularCourts', ${index}, 1)" title="Ke bawah"><i class="fas fa-chevron-down text-xxs"></i></button>
                    <button type="button" class="btn btn-outline-danger btn-xs py-1 px-2" onclick="removeItem('popularCourts', ${index})"><i class="fas fa-trash-alt text-xxs"></i></button>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-12">
                    <label class="text-xxs uppercase font-weight-bold text-muted mb-1 d-block">Description</label>
                    <input type="text" name="popular_courts[${index}][desc]" class="form-control form-control-sm rounded-lg" value="${court.desc || ''}" onchange="popularCourts[${index}].desc = this.value" placeholder="Tulis deksripsi singkat..." required>
                </div>
            </div>
        `;
        return div;
    }

    function addCourtRow() {
        popularCourts.push({ name: '', location: '', desc: '', price: 150000, image: '', status: 'active' });
        renderPopularCourts();
    }


    // --- 3. TESTIMONIALS LIST ---
    function renderTestimonials() {
        const container = document.getElementById('testimonials-list-container');
        container.innerHTML = '';
        testimonials.forEach((test, index) => {
            container.appendChild(createTestimonialRowHtml(test, index));
        });
    }

    function createTestimonialRowHtml(test, index) {
        const div = document.createElement('div');
        div.className = 'list-item-row p-3 mb-3';
        div.innerHTML = `
            <input type="hidden" name="testimonials[${index}][photo]" value="${test.photo || ''}">
            <div class="row align-items-center">
                <div class="col-md-3 mb-2 mb-md-0">
                    <label class="text-xxs uppercase font-weight-bold text-muted mb-1 d-block">Customer Name</label>
                    <input type="text" name="testimonials[${index}][name]" class="form-control form-control-sm rounded-lg font-weight-bold" value="${test.name || ''}" onchange="testimonials[${index}].name = this.value" required>
                </div>
                <div class="col-md-2 mb-2 mb-md-0">
                    <label class="text-xxs uppercase font-weight-bold text-muted mb-1 d-block">Rating</label>
                    <select name="testimonials[${index}][rating]" class="form-control form-control-sm rounded-lg custom-select" onchange="testimonials[${index}].rating = this.value">
                        <option value="5" ${test.rating == 5 ? 'selected' : ''}>⭐⭐⭐⭐⭐ (5)</option>
                        <option value="4" ${test.rating == 4 ? 'selected' : ''}>⭐⭐⭐⭐ (4)</option>
                        <option value="3" ${test.rating == 3 ? 'selected' : ''}>⭐⭐⭐ (3)</option>
                        <option value="2" ${test.rating == 2 ? 'selected' : ''}>⭐⭐ (2)</option>
                        <option value="1" ${test.rating == 1 ? 'selected' : ''}>⭐ (1)</option>
                    </select>
                </div>
                <div class="col-md-2 mb-2 mb-md-0">
                    <label class="text-xxs uppercase font-weight-bold text-muted mb-1 d-block">Status</label>
                    <select name="testimonials[${index}][status]" class="form-control form-control-sm rounded-lg custom-select" onchange="testimonials[${index}].status = this.value">
                        <option value="active" ${test.status === 'active' ? 'selected' : ''}>Active</option>
                        <option value="hidden" ${test.status === 'hidden' ? 'selected' : ''}>Hidden</option>
                    </select>
                </div>
                <div class="col-md-2 mb-2 mb-md-0">
                    <label class="text-xxs uppercase font-weight-bold text-muted mb-1 d-block">Customer Photo</label>
                    <div id="testi_photo_preview_container_${index}" class="mb-2 bg-light p-1 border rounded-lg text-center d-flex align-items-center justify-content-center overflow-hidden" style="height: 50px; ${test.photo ? '' : 'display: none !important;'}">
                        <img src="${test.photo ? storageUrl + '/' + test.photo : ''}" class="img-fluid rounded h-100" style="object-fit: cover;">
                    </div>
                    <div class="custom-file custom-file-sm">
                        <input type="file" name="testimonials[${index}][photo_file]" class="custom-file-input" id="testimonials_photo_${index}" data-preview-container="testi_photo_preview_container_${index}">
                        <label class="custom-file-label rounded-lg form-control-sm" for="testimonials_photo_${index}">Upload...</label>
                    </div>
                </div>
                <div class="col-md-3 text-right mt-3 mt-md-0 d-flex gap-1 justify-content-end">
                    <button type="button" class="btn btn-outline-secondary btn-xs py-1 px-2" onclick="moveItem('testimonials', ${index}, -1)" title="Ke atas"><i class="fas fa-chevron-up text-xxs"></i></button>
                    <button type="button" class="btn btn-outline-secondary btn-xs py-1 px-2" onclick="moveItem('testimonials', ${index}, 1)" title="Ke bawah"><i class="fas fa-chevron-down text-xxs"></i></button>
                    <button type="button" class="btn btn-outline-danger btn-xs py-1 px-2" onclick="removeItem('testimonials', ${index})"><i class="fas fa-trash-alt text-xxs"></i></button>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-12">
                    <label class="text-xxs uppercase font-weight-bold text-muted mb-1 d-block">Review Description</label>
                    <input type="text" name="testimonials[${index}][review]" class="form-control form-control-sm rounded-lg" value="${test.review || ''}" onchange="testimonials[${index}].review = this.value" required>
                </div>
            </div>
        `;
        return div;
    }

    function addTestimonialRow() {
        testimonials.push({ name: '', review: '', rating: 5, photo: '', status: 'active' });
        renderTestimonials();
    }


    // --- 4. MEMBERSHIP LIST ---
    function renderMembership() {
        const container = document.getElementById('membership-list-container');
        container.innerHTML = '';
        membership.forEach((mem, index) => {
            container.appendChild(createMembershipRowHtml(mem, index));
        });
    }

    function createMembershipRowHtml(mem, index) {
        const div = document.createElement('div');
        div.className = 'list-item-row p-3 mb-3';
        div.innerHTML = `
            <div class="row align-items-center">
                <div class="col-md-3 mb-2 mb-md-0">
                    <label class="text-xxs uppercase font-weight-bold text-muted mb-1 d-block">Package Name</label>
                    <input type="text" name="membership[${index}][name]" class="form-control form-control-sm rounded-lg font-weight-bold" value="${mem.name || ''}" onchange="membership[${index}].name = this.value" required>
                </div>
                <div class="col-md-2 mb-2 mb-md-0">
                    <label class="text-xxs uppercase font-weight-bold text-muted mb-1 d-block">Price</label>
                    <input type="number" name="membership[${index}][price]" class="form-control form-control-sm rounded-lg" value="${mem.price || ''}" onchange="membership[${index}].price = this.value" required>
                </div>
                <div class="col-md-2 mb-2 mb-md-0">
                    <label class="text-xxs uppercase font-weight-bold text-muted mb-1 d-block">Duration (Text)</label>
                    <input type="text" name="membership[${index}][duration]" class="form-control form-control-sm rounded-lg" value="${mem.duration || 'Bulan'}" onchange="membership[${index}].duration = this.value" placeholder="Contoh: Bulan, Lifetime" required>
                </div>
                <div class="col-md-2 mb-2 mb-md-0">
                    <label class="text-xxs uppercase font-weight-bold text-muted mb-1 d-block">Status</label>
                    <select name="membership[${index}][status]" class="form-control form-control-sm rounded-lg custom-select" onchange="membership[${index}].status = this.value">
                        <option value="active" ${mem.status === 'active' ? 'selected' : ''}>Active</option>
                        <option value="hidden" ${mem.status === 'hidden' ? 'selected' : ''}>Hidden</option>
                    </select>
                </div>
                <div class="col-md-3 text-right mt-3 mt-md-0 d-flex gap-1 justify-content-end">
                    <button type="button" class="btn btn-outline-secondary btn-xs py-1 px-2" onclick="moveItem('membership', ${index}, -1)" title="Ke atas"><i class="fas fa-chevron-up text-xxs"></i></button>
                    <button type="button" class="btn btn-outline-secondary btn-xs py-1 px-2" onclick="moveItem('membership', ${index}, 1)" title="Ke bawah"><i class="fas fa-chevron-down text-xxs"></i></button>
                    <button type="button" class="btn btn-outline-danger btn-xs py-1 px-2" onclick="removeItem('membership', ${index})"><i class="fas fa-trash-alt text-xxs"></i></button>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-12">
                    <label class="text-xxs uppercase font-weight-bold text-muted mb-1 d-block">Package Features (benefits per line)</label>
                    <textarea name="membership[${index}][features]" class="form-control form-control-sm rounded-lg text-xs" rows="3" placeholder="- Cari Lapangan\n- Booking Online" onchange="membership[${index}].features = this.value" required>${mem.features || ''}</textarea>
                </div>
            </div>
        `;
        return div;
    }

    function addMembershipRow() {
        membership.push({ name: '', price: 0, duration: 'Bulan', features: '', status: 'active' });
        renderMembership();
    }


    // --- 5. REORDER & REMOVE ITEMS UTILITIES ---
    function moveItem(arrayName, index, direction) {
        let arr = [];
        if (arrayName === 'features') arr = features;
        else if (arrayName === 'testimonials') arr = testimonials;
        else if (arrayName === 'popularCourts') arr = popularCourts;
        else if (arrayName === 'membership') arr = membership;

        let targetIndex = index + direction;
        if (targetIndex >= 0 && targetIndex < arr.length) {
            let temp = arr[index];
            arr[index] = arr[targetIndex];
            arr[targetIndex] = temp;
            
            // Re-render and restore state
            renderAll();
        }
    }

    function removeItem(arrayName, index) {
        if (!confirm('Apakah Anda yakin ingin menghapus item ini?')) return;
        
        if (arrayName === 'features') {
            features.splice(index, 1);
            renderFeatures();
        } else if (arrayName === 'testimonials') {
            testimonials.splice(index, 1);
            renderTestimonials();
        } else if (arrayName === 'popularCourts') {
            popularCourts.splice(index, 1);
            renderPopularCourts();
        } else if (arrayName === 'membership') {
            membership.splice(index, 1);
            renderMembership();
        }
    }
</script>
@endpush
