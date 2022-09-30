@extends('admin.layout.auth')
@section("title", "Internal Server Error")

@section('content')
<div class="login-main">
    <div class="login-inner">
        <div class="loginscreen middle-box errorbox">
            <h1>500</h1>
            <p>Internal server error</p>
            <button type="button" id="goBackBtn" class="btn btn-primary block full-width m-b submit-button">Go Back</button>
            <div class="errorlogoimg">
                <img alt="image" src="{{asset('/admins/images/logo.png')}}"/>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    $('#goBackBtn').on('click', function () {
        var previousUrl = document.referrer;
        if (previousUrl == '') {
            window.location.href = "{{ url()->previous() }}";
        } else {
            window.location.href = previousUrl;
        }
    });
</script>
@endpush