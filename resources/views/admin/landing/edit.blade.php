@extends('layouts.admin')

@section('title', 'Manage Landing Page')
@section('header', 'Pengaturan Landing Page')

@section('content')
<div class="row">
    <div class="col-12">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible shadow-sm border-0">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-check"></i> Berhasil!</h5>
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible shadow-sm border-0">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-ban"></i> Terjadi Kesalahan!</h5>
                <ul class="mb-0 mt-1 small">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.landing.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Hero Section -->
            <div class="card card-outline card-primary shadow-sm border-0 mb-4">
                <div class="card-header border-0 mt-2">
                    <h3 class="card-title font-weight-bold text-muted small text-uppercase tracking-wider">
                        Hero Section (Bagian Atas)
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group mb-3">
                                <label for="hero_title" class="text-dark font-weight-bold">Hero Title</label>
                                <input type="text" name="hero_title" id="hero_title" class="form-control border-0 bg-light rounded shadow-none @error('hero_title') is-invalid @enderror" value="{{ old('hero_title', $content->hero_title) }}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="hero_subtitle" class="text-dark font-weight-bold">Hero Subtitle</label>
                                <input type="text" name="hero_subtitle" id="hero_subtitle" class="form-control border-0 bg-light rounded shadow-none" value="{{ old('hero_subtitle', $content->hero_subtitle) }}">
                            </div>
                            <div class="form-group mb-0">
                                <label for="hero_cta_text" class="text-dark font-weight-bold">CTA Button Text</label>
                                <input type="text" name="hero_cta_text" id="hero_cta_text" class="form-control border-0 bg-light rounded shadow-none" value="{{ old('hero_cta_text', $content->hero_cta_text) }}">
                            </div>
                        </div>
                        <div class="col-md-5 mt-4 mt-md-0">
                            <label class="text-dark font-weight-bold d-block">Hero Illustration / Image</label>
                            <div class="p-3 bg-light rounded-lg border-2 border-dashed d-flex flex-column align-items-center justify-content-center text-center">
                                @if($content->hero_image)
                                <div class="mb-3">
                                    <img src="{{ str_starts_with($content->hero_image, 'http') ? $content->hero_image : asset('storage/'.$content->hero_image) }}" alt="Hero Preview" class="img-fluid rounded shadow-sm border" style="max-height: 120px;">
                                </div>
                                @endif
                                <div class="custom-file" style="max-width: 250px;">
                                    <input type="file" name="hero_image" class="custom-file-input" id="hero_image">
                                    <label class="custom-file-label border-0 text-left rounded shadow-sm" for="hero_image">Ganti file...</label>
                                </div>
                                <small class="text-muted mt-2">SVG, PNG, JPG (Max. 2MB)</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- About Section -->
            <div class="card card-outline card-success shadow-sm border-0 mb-4">
                <div class="card-header border-0 mt-2">
                    <h3 class="card-title font-weight-bold text-muted small text-uppercase tracking-wider">
                        About Section (Fasilitas)
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group mb-3">
                                <label for="about_title" class="text-dark font-weight-bold">About Title</label>
                                <input type="text" name="about_title" id="about_title" class="form-control border-0 bg-light rounded shadow-none" value="{{ old('about_title', $content->about_title) }}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="about_subtitle" class="text-dark font-weight-bold">About Subtitle</label>
                                <input type="text" name="about_subtitle" id="about_subtitle" class="form-control border-0 bg-light rounded shadow-none" value="{{ old('about_subtitle', $content->about_subtitle) }}">
                            </div>
                            <div class="form-group mb-0">
                                <label for="about_description" class="text-dark font-weight-bold">About Description</label>
                                <textarea name="about_description" id="about_description" rows="5" class="form-control border-0 bg-light rounded shadow-none" required>{{ old('about_description', $content->about_description) }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-5 mt-4 mt-md-0">
                            <label class="text-dark font-weight-bold d-block">About Image</label>
                            <div class="p-3 bg-light rounded-lg border-2 border-dashed d-flex flex-column align-items-center justify-content-center text-center">
                                @if($content->about_image)
                                <div class="mb-3">
                                    <img src="{{ str_starts_with($content->about_image, 'http') ? $content->about_image : asset('storage/'.$content->about_image) }}" alt="About Preview" class="img-fluid rounded shadow-sm border" style="max-height: 120px;">
                                </div>
                                @endif
                                <div class="custom-file" style="max-width: 250px;">
                                    <input type="file" name="about_image" class="custom-file-input" id="about_image">
                                    <label class="custom-file-label border-0 text-left rounded shadow-sm" for="about_image">Ganti file...</label>
                                </div>
                                <small class="text-muted mt-2">Recommended: 16:9 Aspect Ratio</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Section -->
            <div class="card card-outline card-warning shadow-sm border-0 mb-5">
                <div class="card-header border-0 mt-2">
                    <h3 class="card-title font-weight-bold text-muted small text-uppercase tracking-wider">
                        Informasi Kontak & Footer
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="contact_phone" class="text-dark font-weight-bold">Phone Number</label>
                                <input type="text" name="contact_phone" id="contact_phone" class="form-control border-0 bg-light rounded shadow-none" value="{{ old('contact_phone', $content->contact_phone) }}">
                            </div>
                            <div class="form-group mb-3">
                                <label for="contact_email" class="text-dark font-weight-bold">Email Address</label>
                                <input type="email" name="contact_email" id="contact_email" class="form-control border-0 bg-light rounded shadow-none" value="{{ old('contact_email', $content->contact_email) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="whatsapp_number" class="text-dark font-weight-bold">WhatsApp Number</label>
                                <input type="text" name="whatsapp_number" id="whatsapp_number" class="form-control border-0 bg-light rounded shadow-none" value="{{ old('whatsapp_number', $content->whatsapp_number) }}">
                            </div>
                            <div class="form-group mb-3">
                                <label for="contact_address" class="text-dark font-weight-bold">Physical Address</label>
                                <input type="text" name="contact_address" id="contact_address" class="form-control border-0 bg-light rounded shadow-none" value="{{ old('contact_address', $content->contact_address) }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sticky-top py-3 text-right" style="bottom: 20px; z-index: 1020;">
                <button type="submit" class="btn btn-primary btn-lg shadow-lg px-5 rounded-pill font-weight-bold text-uppercase small tracking-widest">
                    Update Landing Page
                </button>
            </div>
        </form>
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
