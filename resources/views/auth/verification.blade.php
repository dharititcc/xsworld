@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Verification</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            Your email verification is successfull. Please try to login.
                        </div>
                    @endif

                    Your verification is successfull. Please try to login from your app.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
