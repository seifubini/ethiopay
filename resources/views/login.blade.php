@extends('layouts.beforeLogin3')
@section("title", "Login")

@section('content')
<main class="signin-page">
    <div class="eth-card-wrapper">
        <div class="eth-card">
            <div class="logo">
                <img src="{{ asset('img/logo.png') }}">
            </div>
            <form id="loginForm" name="loginForm" class="form-horizontal" method="POST" action="{{ route('login') }}">
                {{ csrf_field() }}
                <div class="eth-form">
                    <div class="form-group field activefield {{ $errors->has('email') ? 'has-error' : '' }}">
                        <input type="text" name="email" id="email" value="{{ old('email') }}" autofocus>
                        <label for="email">Email</label>
                        @if ($errors->has('email'))
                        <span class="help-block input_error">
                            {{ $errors->first('email') }}
                        </span>
                        @endif
                    </div>
                    <div class="form-group field paswd-field {{ $errors->has('password') ? 'has-error' : '' }}">
                        <input type="password" name="password" id="password">
                        <label for="password">Password</label>
                        <i class="hideShowPassword "></i>
                        <!-- <img class="" src="{{ asset('img/fa-eye.svg') }}"> -->
                        @if ($errors->has('password'))
                        <span class="help-block input_error">
                            {{ $errors->first('password') }}
                        </span>
                        @endif
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                        </label>
                    </div>
                </div>
                <button id="submitBtnLoginForm" name="submitBtnLoginForm" type="submit" class="btn btn-primary" data-loading-text="Loading...">Sign In</button>
                <div class="forgot-paswd">
                    <a href="{{ route('password.request') }}">Forgot Password?</a>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection

@push("scripts")
<script type="text/javascript">
    $("#loginForm").validate({
        ignore: [],
        rules: {
            email: {
                required: true,
                email: true,
            },
            password: {
                required: true,
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
            $('#submitBtnLoginForm').button('loading');
            form.submit();
        },
    });
</script>
@endpush

