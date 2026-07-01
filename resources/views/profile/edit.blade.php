@extends(Auth::user()->role === 'admin' ? 'layouts.admin' : 'layouts.public')

@section('title', Auth::user()->role === 'admin' ? 'Admin Profile' : 'Profile Settings')
@section('header', 'Admin Profile')

@section('content')
@if(Auth::user()->role === 'admin')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="mb-4">
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
                            <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', Auth::user()->email) }}" required autocomplete="username">
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

@else
    <div class="py-24 px-4 sm:px-6 lg:px-8 bg-dark min-h-screen relative overflow-hidden" x-data="{ showDeleteModal: false }">
        <!-- Subtle Background Glows -->
        <div class="absolute top-0 left-0 w-96 h-96 bg-neon/5 rounded-full blur-[120px] -ml-48 -mt-48 pointer-events-none"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-lime-500/5 rounded-full blur-[120px] -mr-48 -mb-48 pointer-events-none"></div>

        <div class="max-w-2xl mx-auto relative z-10">
            <!-- Header Section -->
            <div class="text-center mb-16">
                <div class="flex items-center space-x-3 mb-3 justify-center">
                    <div class="h-1 w-8 bg-neon"></div>
                    <span class="text-neon font-black uppercase tracking-[0.25em] text-[10px]">Account Settings</span>
                    <div class="h-1 w-8 bg-neon"></div>
                </div>
                <h3 class="text-4xl font-black text-white italic tracking-tighter uppercase font-heading">
                    PENGATURAN <span class="text-transparent bg-clip-text bg-gradient-to-r from-neon to-lime-500">PROFIL</span>
                </h3>
            </div>

            <!-- Session Feedback Messages -->
            @if (session('status') === 'profile-updated')
                <div class="mb-8 bg-neon/10 border border-neon/20 text-neon px-6 py-4 rounded-2xl font-bold flex items-center shadow-lg">
                    <i class="fas fa-check-circle mr-3 text-lg"></i>
                    Profil Anda berhasil diperbarui.
                </div>
            @endif

            @if (session('status') === 'password-updated')
                <div class="mb-8 bg-neon/10 border border-neon/20 text-neon px-6 py-4 rounded-2xl font-bold flex items-center shadow-lg">
                    <i class="fas fa-check-circle mr-3 text-lg"></i>
                    Password Anda berhasil diperbarui.
                </div>
            @endif

            @if ($errors->userDeletion->has('password'))
                <div class="mb-8 bg-red-500/10 border border-red-500/20 text-red-500 px-6 py-4 rounded-2xl font-bold flex items-center shadow-lg">
                    <i class="fas fa-exclamation-circle mr-3 text-lg"></i>
                    Gagal menghapus akun: Password salah.
                </div>
            @endif

            <div class="space-y-12">
                <!-- 1. Profile Information -->
                <div class="bg-dark-card/40 backdrop-blur-md rounded-[2rem] border border-white/5 p-8 sm:p-10 shadow-2xl">
                    <div class="flex items-center space-x-4 mb-8 pb-6 border-b border-white/5">
                        <div class="w-16 h-16 rounded-2xl bg-neon/10 border border-neon/25 text-neon flex items-center justify-center font-black text-2xl uppercase">
                            {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                        </div>
                        <div>
                            <h4 class="text-white font-black text-lg uppercase italic mb-0">{{ Auth::user()->name }}</h4>
                            <span class="text-white/40 text-[9px] font-black uppercase tracking-widest mt-1 block">Registered Customer</span>
                        </div>
                    </div>

                    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                        @csrf
                        @method('patch')

                        <div class="space-y-2">
                            <label for="name" class="block text-xs font-black text-white/40 uppercase tracking-widest pl-2">Full Name</label>
                            <input id="name" name="name" type="text" value="{{ old('name', Auth::user()->name) }}" required autocomplete="name"
                                   class="block w-full bg-dark border border-white/5 focus:border-neon focus:ring-1 focus:ring-neon rounded-2xl p-5 text-white font-bold transition-all placeholder:text-gray-700 outline-none">
                            @error('name')
                                <span class="text-red-500 text-[10px] font-bold uppercase tracking-widest pl-2 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="email" class="block text-xs font-black text-white/40 uppercase tracking-widest pl-2">Email Address</label>
                            <input id="email" name="email" type="email" value="{{ old('email', Auth::user()->email) }}" required autocomplete="username"
                                   class="block w-full bg-dark border border-white/5 focus:border-neon focus:ring-1 focus:ring-neon rounded-2xl p-5 text-white font-bold transition-all placeholder:text-gray-700 outline-none">
                            @error('email')
                                <span class="text-red-500 text-[10px] font-bold uppercase tracking-widest pl-2 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="pt-4 flex justify-end">
                            <button type="submit" class="bg-neon text-dark px-10 py-4 rounded-2xl font-black uppercase tracking-wider text-xs hover:bg-white hover:scale-105 active:scale-95 transition shadow-lg">
                                Simpan Profil
                            </button>
                        </div>
                    </form>
                </div>

                <!-- 2. Security Update (Password) -->
                <div class="bg-dark-card/40 backdrop-blur-md rounded-[2rem] border border-white/5 p-8 sm:p-10 shadow-2xl">
                    <h4 class="text-white font-black uppercase tracking-widest text-sm mb-8 border-l-4 border-neon pl-4 italic">Security Update</h4>

                    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                        @csrf
                        @method('put')

                        <div class="space-y-2">
                            <label for="current_password" class="block text-xs font-black text-white/40 uppercase tracking-widest pl-2">Current Password</label>
                            <input id="current_password" name="current_password" type="password" autocomplete="current-password"
                                   class="block w-full bg-dark border border-white/5 focus:border-neon focus:ring-1 focus:ring-neon rounded-2xl p-5 text-white font-bold transition-all placeholder:text-gray-700 outline-none">
                            @error('current_password', 'updatePassword')
                                <span class="text-red-500 text-[10px] font-bold uppercase tracking-widest pl-2 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="password" class="block text-xs font-black text-white/40 uppercase tracking-widest pl-2">New Password</label>
                            <input id="password" name="password" type="password" autocomplete="new-password"
                                   class="block w-full bg-dark border border-white/5 focus:border-neon focus:ring-1 focus:ring-neon rounded-2xl p-5 text-white font-bold transition-all placeholder:text-gray-700 outline-none">
                            @error('password', 'updatePassword')
                                <span class="text-red-500 text-[10px] font-bold uppercase tracking-widest pl-2 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="password_confirmation" class="block text-xs font-black text-white/40 uppercase tracking-widest pl-2">Confirm New Password</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                                   class="block w-full bg-dark border border-white/5 focus:border-neon focus:ring-1 focus:ring-neon rounded-2xl p-5 text-white font-bold transition-all placeholder:text-gray-700 outline-none">
                            @error('password_confirmation', 'updatePassword')
                                <span class="text-red-500 text-[10px] font-bold uppercase tracking-widest pl-2 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="pt-4 flex justify-end">
                            <button type="submit" class="bg-neon text-dark px-10 py-4 rounded-2xl font-black uppercase tracking-wider text-xs hover:bg-white hover:scale-105 active:scale-95 transition shadow-lg">
                                Ganti Password
                            </button>
                        </div>
                    </form>
                </div>

                <!-- 3. Danger Zone (Delete Account) -->
                <div class="bg-dark-card/40 backdrop-blur-md rounded-[2rem] border border-red-500/20 p-8 sm:p-10 shadow-2xl">
                    <h4 class="text-red-500 font-black uppercase tracking-widest text-sm mb-4 border-l-4 border-red-500 pl-4 italic">Danger Zone</h4>
                    <p class="text-gray-400 text-xs font-medium mb-8">Setelah akun dihapus, semua data pesanan, langganan membership, dan data akun Anda akan dihapus secara permanen.</p>

                    <div class="flex justify-start">
                        <button type="button" @click="showDeleteModal = true"
                                class="bg-red-500/10 text-red-500 border border-red-500/20 px-8 py-4 rounded-2xl font-black uppercase tracking-wider text-xs hover:bg-red-500 hover:text-white transition shadow-lg">
                            Hapus Akun Saya
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- AlpineJS Modal for Account Deletion (No Bootstrap JS Dependency) -->
        <div x-show="showDeleteModal" 
             class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-dark/80 backdrop-blur-md"
             x-cloak
             x-transition>
            <div class="bg-dark-card border border-white/5 rounded-[2.5rem] max-w-md w-full p-8 shadow-2xl relative"
                 @click.away="showDeleteModal = false">
                
                <h3 class="font-heading italic font-black text-2xl text-white uppercase mb-4">
                    HAPUS <span class="text-red-500">AKUN</span>
                </h3>
                
                <p class="text-gray-400 text-xs font-semibold leading-relaxed mb-6">
                    Apakah Anda yakin ingin menghapus akun? Setelah akun Anda dihapus, seluruh data pesanan, membership, dan kuitansi akan dihapus permanen. Silakan masukkan password Anda untuk mengonfirmasi.
                </p>

                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <div class="space-y-4 mb-8">
                        <input id="delete_password" name="password" type="password" required
                               class="block w-full bg-dark border border-white/5 focus:border-red-500 focus:ring-1 focus:ring-red-500 rounded-2xl p-5 text-white font-bold transition-all placeholder:text-gray-700 outline-none"
                               placeholder="MASUKKAN PASSWORD ANDA">
                    </div>

                    <div class="flex space-x-4">
                        <button type="button" @click="showDeleteModal = false"
                                class="flex-1 bg-white/5 border border-white/10 text-white font-black py-4 rounded-2xl uppercase tracking-wider text-xs hover:bg-white/10 transition">
                            Batal
                        </button>
                        <button type="submit"
                                class="flex-1 bg-red-500 text-white font-black py-4 rounded-2xl uppercase tracking-wider text-xs hover:bg-red-600 transition shadow-lg shadow-red-500/30">
                            Konfirmasi Hapus
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
@endsection
