@extends('layouts.app')

@section('content')
<div class="d-flex flex-column justify-content-between h-100">
    <div class="moudle-box">
        <span>XSBar</span>
        Terminal Module
    </div>

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="container">
            <img src="{{ asset('img/logo.png') }}" alt="" class="logo">
            <div class="form-group">
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="{{ __('Email Address') }}" required autocomplete="email" autofocus>

                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="btn-box">
                <button type="submit" class="btn btn-border w-100">
                    {{ __('Send Password Reset Link') }}
                </button>
            </div>
        </div>
    </form>
    <div class="moudle-box">Version 0.1</div>
</div>
@endsection