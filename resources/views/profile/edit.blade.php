@extends('layouts.public')

@section('title', 'Profile Settings')

@section('content')
<div class="py-24 px-4 sm:px-6 lg:px-8 bg-dark min-h-screen">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-16">
            <h2 class="text-neon font-black uppercase tracking-[.3em] text-xs mb-4">Account Settings</h2>
            <h3 class="text-5xl font-black text-white italic tracking-tighter uppercase font-heading">UPDATE <span class="underline decoration-neon underline-offset-8">PROFILE</span></h3>
        </div>

        @if (session('status') === 'profile-updated')
            <div class="mb-8 bg-neon/10 border border-neon/20 text-neon px-6 py-4 rounded-2xl font-bold flex items-center shadow-lg animate-pulse">
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Profile updated successfully.
            </div>
        @endif

        <div class="space-y-12">
            <!-- Profile Info -->
            <div class="bg-dark-card rounded-[2.5rem] border border-white/5 p-10 shadow-2xl">
                <h4 class="text-white font-black uppercase tracking-widest text-sm mb-8 border-l-4 border-neon pl-4 italic">Profile Information</h4>
                <div class="text-gray-300">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Update Password -->
            <div class="bg-dark-card rounded-[2.5rem] border border-white/5 p-10 shadow-2xl">
                <h4 class="text-white font-black uppercase tracking-widest text-sm mb-8 border-l-4 border-neon pl-4 italic">Security Update</h4>
                <div class="text-gray-300">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete Account -->
            <div class="bg-dark-card rounded-[2.5rem] border border-red-500/20 p-10 shadow-2xl">
                <h4 class="text-red-500 font-black uppercase tracking-widest text-sm mb-8 border-l-4 border-red-500 pl-4 italic">Danger Zone</h4>
                <div class="text-gray-300">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    label {
        color: white !important;
        font-weight: 700 !important;
        letter-spacing: 0.05em !important;
        margin-bottom: 0.5rem !important;
        display: block !important;
    }
    input, select, textarea {
        background-color: rgba(15, 23, 42, 0.8) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        color: white !important;
        border-radius: 0.75rem !important;
        padding: 0.75rem 1rem !important;
        width: 100% !important;
    }
    input:focus {
        border-color: #bef264 !important;
        box-shadow: 0 0 0 2px rgba(190, 242, 100, 0.2) !important;
        background-color: #0f172a !important;
    }
    button[type="submit"] {
        background-color: #bef264 !important;
        color: #0f172a !important;
        font-weight: 900 !important;
        text-transform: uppercase !important;
        padding: 0.75rem 2rem !important;
        border-radius: 0.75rem !important;
        letter-spacing: 0.05em !important;
        transition: all 0.3s ease !important;
    }
    button[type="submit"]:hover {
        background-color: white !important;
        transform: translateY(-2px) !important;
    }
    .text-sm.text-gray-600, .text-sm.text-secondary { 
        color: #94a3b8 !important; 
        font-weight: 500 !important;
    }
    /* Override specific visibility issues */
    .text-dark { color: white !important; }
    .text-secondary { color: #94a3b8 !important; }
    .bg-dark-card { background-color: rgba(30, 41, 59, 0.7) !important; }
    .modal-backdrop.show { opacity: 0.8 !important; backdrop-filter: blur(8px) !important; }
    .close:focus { outline: none !important; }
</style>
@endpush
@endsection
