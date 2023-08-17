@extends('layouts.app')

@section('content')
<div class="d-flex flex-column justify-content-between h-100">
    <div class="moudle-box" >
        <span>XSBar</span>
        Terminal Module
    </div>

    <form class="form-signin" method="POST" action="{{ route('login') }}">
        @csrf

      <div class="container">
        <img src="img/logo.svg" alt="" class="logo">

      <div class="form-group">
        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Email address" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
      </div>

      <div class="form-group">
        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
      </div>


      <div class="btn-box">
        <button class="btn btn-border w-100" type="submit">Login</button>

        <div class="d-flex mt-4 justify-content-between bottom">
        <button class="btn btn-border" type="button">Support</button>
        <button class="btn btn-border" type="button">Register</button>
      </div>
      </div>
    </div>
  </form>
    <div class="moudle-box">Version 0.1</div>


</div>
@endsection
