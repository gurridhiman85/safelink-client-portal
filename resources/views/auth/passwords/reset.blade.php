@extends('layouts.guest')

@section('content')

    <div class="auth-form-light text-left py-5 px-4 px-sm-5">
        @include('layouts.app-logo-row')

        <h6 class="fw-light">{{ __('Reset Password') }}</h6>
        <form class="pt-3" method="POST" action="{{ route('password.update') }}">
            <input type="hidden" name="token" value="{{ $token }}">
            @csrf

            <div class="form-group">
                <input id="email" type="email" class="form-control  form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email" autofocus>
                @error('email')
                <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
                @enderror
            </div>
            <div class="form-group">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required placeholder="Password" autocomplete="current-password">

                @error('password')
                <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
                @enderror
            </div>

            <div class="form-group">
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation"
                       required placeholder="Confirm Password" autocomplete="new-password">

                @error('password_confirmation')
                <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
                @enderror
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">
                    {{ __('Reset Password') }}
                </button>
            </div>

        </form>
    </div>
@endsection
