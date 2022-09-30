@extends('admin.layout.auth')
@section("title", "Reset Password")

<!-- Main Content -->
@section('content')
<div class="login-main">
    <div class="login-inner">
        <div class="loginscreen middle-box">
            <div class="text-center center-block">
                <img alt="image"  src="{{asset('/admins/images/logo.png')}}"  alt="{{ config('app.name', 'Reset Password') }}" />
            </div>
            <p>Reset Password</p>
            <form class="m-t validateForm beforeloginform" id="resetForm" role="form" method="POST" action="{{ url('/admin/password/email') }}">
                {{ csrf_field() }}
                <div class="form-group field">
                    <input type="email" class="form-control" name="email" value="{{ old('email') }}" autofocus>
                    <label>Email</label>
                </div>
                <button type="submit" class="btn btn-primary block full-width m-b submit-button" data-loading-text="Loading...">Send</button>
                <div class="forgot-paswd">
                    <a class="btn btn-link" href="{{ url('/admin/login') }}">
                        Sign In?
                    </a>
                </div>
            </form>
        </div>
        @if (isset($errors) && $errors->has('email'))
        <div class="errormsgbox">
            <span>{{ $errors->first('email') }}</span>
        </div>
        @endif
        @if (session('status'))
        <div class="successmsgbox alert alert-success">
            <span>{{ session('status') }}</span>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    $("#resetForm").validate({
        errorClass: 'text-danger text-left text-bold',
        errorElement: 'div',
        rules: {
            email: {
                required: true,
                email: true,
            }
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


