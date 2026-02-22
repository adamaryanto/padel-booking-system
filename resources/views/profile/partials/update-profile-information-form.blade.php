<section>
    <header class="mb-4">
        <p class="text-sm text-secondary">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-4">
        @csrf
        @method('patch')

        <div class="form-group mb-3">
            <label for="name" class="font-weight-bold text-white small text-uppercase tracking-wider">{{ __('Name') }}</label>
            <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-4">
            <label for="email" class="font-weight-bold text-white small text-uppercase tracking-wider">{{ __('Email') }}</label>
            <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="text-danger small font-weight-bold">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="btn btn-link btn-sm p-0 align-baseline font-weight-bold">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-weight-bold text-success small">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="d-flex align-items-center">
            <button type="submit" class="btn btn-primary px-4 font-weight-bold rounded-pill">
                {{ __('SAVE CHANGES') }}
            </button>

            @if (session('status') === 'profile-updated')
                <span class="ml-3 text-success small font-weight-bold animated--fade-in">
                    <i class="fas fa-check mr-1"></i>{{ __('Saved.') }}
                </span>
            @endif
        </div>
    </form>
</section>

