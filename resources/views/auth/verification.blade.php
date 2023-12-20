@extends('layouts.app')

@section('content')
<div class="d-flex flex-column justify-content-between h-100">
    <div class="moudle-box">
		<span>XSBar</span>
		Terminal Module
	</div>

    <form class="form-signin" method="POST" action="{{ route('login') }}">
        <div class="container text-center">
            <img src="{{ asset('img/logo.png') }}" alt="" class="logo">

            <div class="form-group green-box">
                @if (session('resent'))
                    <div class="alert alert-success" role="alert">
                        Your email verification is successfull. Please try to login.
                    </div>
                @endif

                Your verification is successfull. Please try to login from your app.
            </div>
        </div>
    </form>

    <div class="moudle-box">Version 0.1</div>
</div>
@endsection
