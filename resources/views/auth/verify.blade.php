@extends('layouts.guest')

@section('content')
<div class="auth-form-light text-left py-5 px-4 px-sm-5">
    @include('layouts.app-logo-row')

    @if (session('resent'))
        <div class="alert alert-success" role="alert">
            {{ __('A fresh verification link has been sent to your email address.') }}
        </div>
    @endif

    {{ __('Before proceeding, please check your email for a verification link.') }}
    {{ __('If you did not receive the email') }},
    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
        @csrf
        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.
    </form>
    <div class="text-center mt-4 fw-light">
        <a href="{{ route('login') }}" class="text-primary">Login</a>
    </div>
</div>

@endsection
