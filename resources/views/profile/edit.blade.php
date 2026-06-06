@if(Auth::user()->role === 'admin')
    @extends('layouts.admin')

    @section('title', 'Admin Profile')
    @section('header', 'Admin Profile')

    @section('content')
    <div class="mb-4 mt-n2">
        <p class="text-muted text-sm font-weight-medium">Kelola informasi profil dan kredensial keamanan akun administrator Anda.</p>
    </div>

    @if (session('status') === 'profile-updated')
        <div class="alert alert-success alert-dismissible shadow-sm border-0 mb-4" style="border-radius: 1rem !important;">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-check"></i> Berhasil!</h5>
            Profil Anda berhasil diperbarui.
        </div>
    @endif

    @if (session('status') === 'password-updated')
        <div class="alert alert-success alert-dismissible shadow-sm border-0 mb-4" style="border-radius: 1rem !important;">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-check"></i> Berhasil!</h5>
            Password Anda berhasil diperbarui.
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <!-- Profile Info Form Card -->
            <div class="card border-gray-200 bg-white mb-4" style="border-radius: 1.5rem !important;">
                <div class="card-header border-0 bg-transparent pt-4 px-4 pb-0">
                    <h5 class="font-weight-bold text-dark mb-0">Informasi Profil</h5>
                </div>
                <div class="card-body p-4">
                    <!-- Initials Avatar Preview -->
                    <div class="d-flex align-items-center mb-5 pb-4 border-bottom">
                        <div class="img-circle bg-success d-inline-flex align-items-center justify-content-center text-white font-weight-bold shadow-sm" style="width: 70px; height: 70px; background-color: #10b981 !important; font-size: 2rem;">
                            {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                        </div>
                        <div class="ml-4">
                            <h6 class="font-weight-bold text-dark mb-1">{{ Auth::user()->name }}</h6>
                            <span class="badge badge-success text-uppercase text-xs" style="padding: 0.35rem 0.75rem;">Administrator</span>
                        </div>
                    </div>

                    <form method="post" action="{{ route('profile.update') }}">
                        @csrf
                        @method('patch')

                        <!-- Full Name -->
                        <div class="form-group mb-4">
                            <label for="name" class="font-weight-bold text-xs uppercase tracking-wider text-muted mb-2 d-block">Full Name <span class="text-danger">*</span></label>
                            <input id="name" name="name" type="text" class="form-control rounded-xl @error('name') is-invalid @enderror" value="{{ old('name', Auth::user()->name) }}" required autocomplete="name">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="form-group mb-4">
                            <label for="email" class="font-weight-bold text-xs uppercase tracking-wider text-muted mb-2 d-block">Email Address <span class="text-danger">*</span></label>
                            <input id="email" name="email" type="email" class="form-control rounded-xl @error('email') is-invalid @enderror" value="{{ old('email', Auth::user()->email) }}" required autocomplete="username">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Role (Read-only) -->
                        <div class="form-group mb-4">
                            <label for="role" class="font-weight-bold text-xs uppercase tracking-wider text-muted mb-2 d-block">Role</label>
                            <input id="role" type="text" class="form-control rounded-xl bg-light text-muted" value="Administrator" readonly style="cursor: not-allowed;">
                        </div>

                        <!-- Submit -->
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary px-4 py-2.5 rounded-xl font-weight-bold shadow-sm">
                                Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Password Form Card -->
            <div class="card border-gray-200 bg-white mb-4" style="border-radius: 1.5rem !important;">
                <div class="card-header border-0 bg-transparent pt-4 px-4 pb-0">
                    <h5 class="font-weight-bold text-dark mb-0">Update Password</h5>
                </div>
                <div class="card-body p-4">
                    <form method="post" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')

                        <!-- Current Password -->
                        <div class="form-group mb-4">
                            <label for="update_password_current_password" class="font-weight-bold text-xs uppercase tracking-wider text-muted mb-2 d-block">Current Password</label>
                            <input id="update_password_current_password" name="current_password" type="password" class="form-control rounded-xl @error('current_password', 'updatePassword') is-invalid @enderror" autocomplete="current-password">
                            @error('current_password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div class="form-group mb-4">
                            <label for="update_password_password" class="font-weight-bold text-xs uppercase tracking-wider text-muted mb-2 d-block">New Password</label>
                            <input id="update_password_password" name="password" type="password" class="form-control rounded-xl @error('password', 'updatePassword') is-invalid @enderror" autocomplete="new-password">
                            @error('password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-group mb-4">
                            <label for="update_password_password_confirmation" class="font-weight-bold text-xs uppercase tracking-wider text-muted mb-2 d-block">Confirm Password</label>
                            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control rounded-xl @error('password_confirmation', 'updatePassword') is-invalid @enderror" autocomplete="new-password">
                            @error('password_confirmation', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit -->
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary px-4 py-2.5 rounded-xl font-weight-bold shadow-sm">
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endsection

@else
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
@endif
