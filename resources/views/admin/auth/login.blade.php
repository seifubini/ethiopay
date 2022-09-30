@extends('admin.layout.auth')
@section("title", "Login")

@section('content')
<div class="login-main">
    <div class="login-inner">
        <div class="loginscreen middle-box">  
            <div class="text-center center-block">
                <img alt="image"  src="{{asset('/admins/images/logo.png')}}"  alt="{{ config('app.name', 'Login') }}" />
            </div>
            <form class="m-t validateForm beforeloginform" id="loginform" role="form" method="POST" action="{{ url('/admin/login') }}">
                {{ csrf_field() }}
                <div class="form-group field">                    
                    <input type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
                    <label>Email</label>
                </div>
                <div class="form-group field paswd-field">
                    <input type="password" class="form-control" name="password" required="">
                    <label>Password</label>
                    <i class="hideShowPassword "></i>
                    @if (isset($errors) && $errors->has('password'))
                    <span class="help-block text-danger">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                    @endif
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="remember"> Remember Me
                    </label>
                </div>
                <button type="submit" class="btn btn-primary block full-width m-b submit-button" data-loading-text="Loading...">Login</button> 
                <div class="forgot-paswd">
                    <a class="btn btn-link" href="{{ url('/admin/password/reset') }}">
                        Forgot Your Password?
                    </a>
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
<script type="text/javascript">
    $("#loginform").validate({
        errorClass: 'text-danger text-left text-bold',
        errorElement: 'div',
        rules: {
            email: {
                required: true,
                email: true,
            },
            password: {
                required: true,
            }
        },
        messages: {
            email: {
                required: 'Email is required.'
            },
            password: {
                required: 'Password is required'
            },
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