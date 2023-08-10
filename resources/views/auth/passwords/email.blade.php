@extends('layouts.guest')

@section('content')
    <div class="auth-form-light text-left py-5 px-4 px-sm-5">
        @include('layouts.app-logo-row')

        <h6 class="fw-light">{{ __('Reset Password') }}</h6>
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <form class="pt-3" method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="form-group">
                <input id="email" type="email" class="form-control  form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                @error('email')
                <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
                @enderror
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">
                    {{ __('Send Password Reset Link') }}
                </button>
            </div>

            <div class="text-center mt-4 fw-light">
                <a href="{{ route('login') }}" class="text-primary">Login</a>
            </div>
        </form>

    </div>
@endsection
