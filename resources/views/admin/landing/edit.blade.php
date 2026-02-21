@extends('layouts.admin')

@section('title', 'Manage Landing Page')
@section('header', 'Pengaturan Landing Page')

@section('content')
<div class="space-y-6">
    @if(session('success'))
        <div id="alert-success" class="flex items-center p-4 mb-4 text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 border border-green-200 dark:border-green-800" role="alert">
            <i data-lucide="check-circle" class="flex-shrink-0 w-4 h-4"></i>
            <span class="sr-only">Success</span>
            <div class="ms-3 text-sm font-medium">
                {{ session('success') }}
            </div>
            <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-green-400 dark:hover:bg-gray-700" data-dismiss-target="#alert-success" aria-label="Close">
                <span class="sr-only">Close</span>
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div id="alert-error" class="flex p-4 mb-4 text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 border border-red-200 dark:border-red-800" role="alert">
            <i data-lucide="alert-circle" class="flex-shrink-0 w-4 h-4 mt-0.5"></i>
            <span class="sr-only">Error</span>
            <div class="ms-3 text-sm font-medium">
                <p class="font-bold mb-1">Terjadi kesalahan:</p>
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-red-400 dark:hover:bg-gray-700" data-dismiss-target="#alert-error" aria-label="Close">
                <span class="sr-only">Close</span>
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
    @endif

    <form action="{{ route('admin.landing.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Hero Section -->
        <div class="p-4 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700">
            <div class="flex items-center mb-4 pb-4 border-b border-gray-100 dark:border-gray-700">
                <i data-lucide="image" class="w-5 h-5 text-primary-500 me-2"></i>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Hero Section</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label for="hero_title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Hero Title</label>
                        <input type="text" name="hero_title" id="hero_title" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 @error('hero_title') border-red-500 @enderror" value="{{ old('hero_title', $content->hero_title) }}" required>
                        @error('hero_title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="hero_subtitle" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Hero Subtitle</label>
                        <input type="text" name="hero_subtitle" id="hero_subtitle" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 @error('hero_subtitle') border-red-500 @enderror" value="{{ old('hero_subtitle', $content->hero_subtitle) }}">
                    </div>
                    <div>
                        <label for="hero_cta_text" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">CTA Button Text</label>
                        <input type="text" name="hero_cta_text" id="hero_cta_text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 @error('hero_cta_text') border-red-500 @enderror" value="{{ old('hero_cta_text', $content->hero_cta_text) }}">
                    </div>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="hero_image">Hero Image</label>
                    <input name="hero_image" id="hero_image" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" type="file">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-300">SVG, PNG, JPG or WebP (Max. 2MB).</p>
                    
                    @if($content->hero_image)
                    <div class="mt-4 p-2 bg-gray-50 dark:bg-gray-900 rounded-lg border border-dashed border-gray-300 dark:border-gray-600">
                        <p class="text-xs font-medium text-gray-500 mb-2 uppercase tracking-tight">Preview Sekarang:</p>
                        <img src="{{ str_starts_with($content->hero_image, 'http') ? $content->hero_image : asset('storage/'.$content->hero_image) }}" alt="Hero Preview" class="rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 max-h-48 object-cover">
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- About Section -->
        <div class="p-4 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700">
            <div class="flex items-center mb-4 pb-4 border-b border-gray-100 dark:border-gray-700">
                <i data-lucide="info" class="w-5 h-5 text-primary-500 me-2"></i>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">About Section (Fasilitas)</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label for="about_title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">About Title</label>
                        <input type="text" name="about_title" id="about_title" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" value="{{ old('about_title', $content->about_title) }}" required>
                    </div>
                    <div>
                        <label for="about_subtitle" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">About Subtitle</label>
                        <input type="text" name="about_subtitle" id="about_subtitle" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" value="{{ old('about_subtitle', $content->about_subtitle) }}">
                    </div>
                    <div>
                        <label for="about_description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">About Description</label>
                        <textarea name="about_description" id="about_description" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>{{ old('about_description', $content->about_description) }}</textarea>
                    </div>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="about_image">About Image</label>
                    <input name="about_image" id="about_image" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" type="file">
                    
                    @if($content->about_image)
                    <div class="mt-4 p-2 bg-gray-50 dark:bg-gray-900 rounded-lg border border-dashed border-gray-300 dark:border-gray-600">
                        <p class="text-xs font-medium text-gray-500 mb-2 uppercase tracking-tight">Preview Sekarang:</p>
                        <img src="{{ str_starts_with($content->about_image, 'http') ? $content->about_image : asset('storage/'.$content->about_image) }}" alt="About Preview" class="rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 max-h-48 object-cover">
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Contact Section -->
        <div class="p-4 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700">
            <div class="flex items-center mb-4 pb-4 border-b border-gray-100 dark:border-gray-700">
                <i data-lucide="phone" class="w-5 h-5 text-primary-500 me-2"></i>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Contact Information</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label for="contact_phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Phone</label>
                    <input type="text" name="contact_phone" id="contact_phone" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" value="{{ old('contact_phone', $content->contact_phone) }}">
                </div>
                <div>
                    <label for="contact_email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                    <input type="email" name="contact_email" id="contact_email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" value="{{ old('contact_email', $content->contact_email) }}">
                </div>
                <div>
                    <label for="whatsapp_number" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">WhatsApp</label>
                    <input type="text" name="whatsapp_number" id="whatsapp_number" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" value="{{ old('whatsapp_number', $content->whatsapp_number) }}">
                </div>
                <div>
                    <label for="contact_address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Address</label>
                    <input type="text" name="contact_address" id="contact_address" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" value="{{ old('contact_address', $content->contact_address) }}">
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end pt-4">
            <button type="submit" class="text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-bold rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-500 dark:hover:bg-primary-600 dark:focus:ring-primary-800 inline-flex items-center shadow-lg shadow-primary-500/30 uppercase tracking-widest">
                <i data-lucide="save" class="w-4 h-4 me-2"></i>
                Update Landing Page
            </button>
        </div>
    </form>
</div>
@endsection
