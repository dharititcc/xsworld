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
            <div class="row mb-3">
                <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                <div class="col-md-6">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-0">
                <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-border w-100">
                        {{ __('Send Password Reset Link') }}
                    </button>
                </div>
            </div>
        </div>
    </form>
    <div class="moudle-box">Version 0.1</div>
</div>
@endsection