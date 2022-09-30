@extends('admin.layout.auth')
@section("title", "Reset Password")

@section('content')
<div class="login-main">
    <div class="login-inner">
        <div class="loginscreen middle-box">
            <div class="text-center center-block">
                <img alt="image"  src="{{asset('/admins/images/logo.png')}}"  alt="{{ config('app.name', 'Reset Password') }}" />
            </div>
            <p>Reset Password</p>
            <form class="m-t validateForm beforeloginform" id="resetForm" role="form" method="POST" action="{{ url('/admin/password/reset') }}">
                {{ csrf_field() }}
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="form-group field {{-- $errors->has('email') ? ' has-error' : '' --}}">
                    <input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}" autofocus>
                    <label>Email</label>
                </div>
                <div class="form-group field paswd-field {{ $errors->has('password') ? ' has-error' : '' }}">
                    <input id="password" type="password" class="form-control" name="password">
                    <label>Password</label>
                    <i class="hideShowPassword "></i>
                    @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                    @endif
                </div>
                <div class="form-group field paswd-field {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
                    <label>Confirm Password</label>
                    <i class="hideShowPassword "></i>
                    @if ($errors->has('password_confirmation'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                    </span>
                    @endif
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary block full-width m-b submit-button" data-loading-text="Loading...">
                        Reset Password
                    </button>
                </div>
            </form>
        </div>
        @if (isset($errors) && $errors->has('email'))
        <div class="errormsgbox">
            <span>{{ $errors->first('email') }}</span>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    jQuery.validator.addMethod("validation_rule_password", function (value, element) {
        var regExp = /^(?=.*[0-9])(?=.*[A-Z]).+$/;
        return this.optional(element) || regExp.test(value);
    }, "Password must contain at least one number and one uppercase letter.");
    
    $("#resetForm").validate({
        errorClass: 'text-danger text-left text-bold',
        errorElement: 'div',
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
                equalTo: "#password"
            },
        },
        messages: {
            email: {
                required: 'Email is required.'
            }
        },
        submitHandler: function (form) {
            $('.submit-button').button('loading');
            form.submit();
        },
        highlight: function (element) {
            $(element).closest('.form-control').addClass('has-error');
        },
        unhighlight: function (element) {
            $(element).closest('.form-control').removeClass('has-error');
        },
    });
</script>
@endpush