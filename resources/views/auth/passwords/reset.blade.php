@extends('layouts.beforeLogin3')
@section("title", "Reset Password")

@section('content')
<main class="forgot-paswd-page">
    <div class="eth-card-wrapper">
        <div class="eth-card">
            <div class="logo">
                <img src="{{ asset('img/logo.png') }}">
            </div>
            <div class="forgot-paswd">
                <a href="javascript:void(0)">Reset Password</a>
            </div>
            <form id="resetPasswordForm" name="resetPasswordForm" class="form-horizontal" method="POST" action="{{ route('password.request') }}">
                {{ csrf_field() }}
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="eth-form">
                    <div class="form-group field {{ $errors->has('email') ? 'has-error' : '' }}">
                        <input type="text" name="email" id="email" value="{{ $email or old('email') }}" autofocus>
                        <label for="email">Email</label>
                        @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="form-group field {{ $errors->has('password') ? 'has-error' : '' }}">
                        <input id="password" name="password" type="password">
                        <label for="password">Password</label>
                        @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="form-group field {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
                        <input id="password_confirmation" name="password_confirmation" type="password">
                        <label for="password_confirmation">Confirm Password</label>
                        @if ($errors->has('password_confirmation'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <button type="submit" id="submitBtnResetPasswordForm" name="submitBtnResetPasswordForm" class="btn btn-primary" data-loading-text="Loading...">Reset Password</button>
            </form>
        </div>
    </div>
</main>
@endsection

@push("scripts")
<script type="text/javascript">
    jQuery.validator.addMethod("validation_rule_password", function (value, element) {
        var regExp = /^(?=.*[0-9])(?=.*[A-Z]).+$/;
        return this.optional(element) || regExp.test(value);
    }, "Password must contain at least one number and one uppercase letter.");

    $("#resetPasswordForm").validate({
        ignore: [],
        rules: {
            email: {
                required: true,
                email: true,
            },
            password: {
                required: true,
                minlength: 8,
                validation_rule_password: true,
            },
            password_confirmation: {
                required: true,
                equalTo : "#password"
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
            $('#submitBtnResetPasswordForm').button('loading');
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
                    <form class="form-horizontal" method="POST" action="{{ route('password.request') }}">
{{ csrf_field() }}

<input type="hidden" name="token" value="{{ $token }}">

<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
    <label for="email" class="col-md-4 control-label">E-Mail Address</label>

    <div class="col-md-6">
        <input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}" required autofocus>

        @if ($errors->has('email'))
        <span class="help-block">
            <strong>{{ $errors->first('email') }}</strong>
        </span>
        @endif
    </div>
</div>

<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
    <label for="password" class="col-md-4 control-label">Password</label>

    <div class="col-md-6">
        <input id="password" type="password" class="form-control" name="password" required>

        @if ($errors->has('password'))
        <span class="help-block">
            <strong>{{ $errors->first('password') }}</strong>
        </span>
        @endif
    </div>
</div>

<div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
    <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>
    <div class="col-md-6">
        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>

        @if ($errors->has('password_confirmation'))
        <span class="help-block">
            <strong>{{ $errors->first('password_confirmation') }}</strong>
        </span>
        @endif
    </div>
</div>

<div class="form-group">
    <div class="col-md-6 col-md-offset-4">
        <button type="submit" class="btn btn-primary">
            Reset Password
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