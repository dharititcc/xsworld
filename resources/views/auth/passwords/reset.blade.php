@extends('layouts.app')

@section('content')
<div class="d-flex flex-column justify-content-between h-100">
    <div class="moudle-box">
        <span>XSBar</span>
        Terminal Module
    </div>
    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="container">
            <img src="{{ asset('img/logo.png') }}" alt="" class="logo">

            <div class="form-group">
                <input id="email" type="email" class="form-control d-none @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" placeholder="{{ __('Email Address') }}" required autocomplete="email">

                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="{{ __('Password') }}" required autocomplete="new-password" autofocus>

                @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group">
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="{{ __('Confirm Password') }}" required autocomplete="new-password">
            </div>

            <div class="btn-box">
                <button type="submit" class="btn btn-border w-100">
                    {{ __('Reset Password') }}
                </button>
            </div>
        </div>
    </form>
    <div class="moudle-box">Version 0.1</div>
</div>
@endsection