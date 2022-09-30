@extends('layouts.beforeLogin3')
@section("title", "Forgot Password")

@section('content')
<main class="forgot-paswd-page">
    <div class="eth-card-wrapper">
        <div class="eth-card">
            <div class="logo">
                <img src="{{ asset('img/logo.png') }}">
            </div>
            <div class="forgot-paswd">
                <a href="javascript:void(0)">Forgot Password</a>
            </div>
            @if (session('status'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <form id="forgotPasswordForm" name="forgotPasswordForm" class="form-horizontal" method="POST" action="{{ route('password.email') }}">
                {{ csrf_field() }}
                <div class="eth-form">
                    <div class="form-group field {{ $errors->has('email') ? 'has-error' : '' }}">
                        <input type="text" name="email" id="email" value="{{ old('email') }}">
                        <label for="email">Email</label>
                        @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <button type="submit" id="submitBtnForgotPasswordForm" name="submitBtnForgotPasswordForm" class="btn btn-primary" data-loading-text="Loading...">Recover Password</button>
            </form>
            <div class="forgot-paswd">
                <a href="{{ route('login') }}">Sign In</a>
            </div>
        </div>
    </div>
</main>
@endsection

@push("scripts")
<script type="text/javascript">
    $("#forgotPasswordForm").validate({
        ignore: [],
        rules: {
            email: {
                required: true,
                email: true,
            },
        }, messages: {
        }, errorPlacement: function (error, element) {
            //error.insertAfter(element.parent());
            error.insertAfter(element);
        }, highlight: function (element) {

        }, unhighlight: function (element) {

        },
        errorElement: 'span',
        errorClass: 'input_error',
        submitHandler: function (form) {
            $('#submitBtnForgotPasswordForm').button('loading');
            form.submit();
        },
    });
</script>
@endpush

{{--

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Reset Password</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
</div>
@endif

<form class="form-horizontal" method="POST" action="{{ route('password.email') }}">
    {{ csrf_field() }}

    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
        <label for="email" class="col-md-4 control-label">E-Mail Address</label>

        <div class="col-md-6">
            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

            @if ($errors->has('email'))
            <span class="help-block">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
            @endif
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-6 col-md-offset-4">
            <button type="submit" class="btn btn-primary">
                Send Password Reset Link
            </button>
        </div>
    </div>
</form>
</div>
</div>
</div>
</div>
</div>
@endsection
--}}