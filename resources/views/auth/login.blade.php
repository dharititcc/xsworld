@extends('layouts.app')

@section('content')
<div class="d-flex flex-column justify-content-between h-100">
	<div class="moudle-box">
		<span>XSBar</span>
		Terminal Module
	</div>

	<form class="form-signin" method="POST" action="{{ route('login') }}">
		@csrf

		<div class="container">
			<img src="{{ asset('img/logo.png') }}" alt="" class="logo">

			<div class="form-group">
				<input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Email *" value="{{ old('email') }}" required autocomplete="email" autofocus>

				@error('email')
				<span class="invalid-feedback" role="alert">
					<strong>{{ $message }}</strong>
				</span>
				@enderror
			</div>

			<div class="form-group">
				<input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password *" placeholder="Password" required autocomplete="current-password">

				@error('password')
				<span class="invalid-feedback" role="alert">
					<strong>{{ $message }}</strong>
				</span>
				@enderror
			</div>


			<div class="btn-box">
				<button class="btn btn-border w-100" type="submit">Login</button>
				<a href="{{ route('password.request') }}" class="btn btn-border w-100 mt-4" type="button">Forgot Password</a>
			</div>
		</div>
	</form>
	<div class="moudle-box">Version 0.1</div>


</div>
@endsection