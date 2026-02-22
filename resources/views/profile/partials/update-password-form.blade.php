<section>
    <header class="mb-4">
        <p class="text-sm text-secondary">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-4">
        @csrf
        @method('put')

        <div class="form-group mb-3">
            <label for="current_password" class="font-weight-bold text-white small text-uppercase tracking-wider">{{ __('Current Password') }}</label>
            <input id="update_password_current_password" name="current_password" type="password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" autocomplete="current-password">
            @error('current_password', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="password" class="font-weight-bold text-white small text-uppercase tracking-wider">{{ __('New Password') }}</label>
            <input id="update_password_password" name="password" type="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror" autocomplete="new-password">
            @error('password', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-4">
            <label for="password_confirmation" class="font-weight-bold text-white small text-uppercase tracking-wider">{{ __('Confirm Password') }}</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror" autocomplete="new-password">
            @error('password_confirmation', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex align-items-center">
            <button type="submit" class="btn btn-primary px-4 font-weight-bold rounded-pill">
                {{ __('UPDATE PASSWORD') }}
            </button>

            @if (session('status') === 'password-updated')
                <span class="ml-3 text-success small font-weight-bold animated--fade-in">
                    <i class="fas fa-check mr-1"></i>{{ __('Saved.') }}
                </span>
            @endif
        </div>
    </form>
</section>

