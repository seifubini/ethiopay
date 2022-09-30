<div class="modal fade session_popup" id="confirmSessionModel" tabindex="-1" role="dialog" aria-labelledby="editconfirmSessionModelLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="session_inner">
                <form name="confirmSession_form" id="confirmSession_form" class="" action="javascript:void(0);">
                    <img src="{{asset('img/warning_icon.png')}}" alt="warningicon">
                    <h2>Your session is about to expire!</h2>
                    <input type="hidden" name="pkgeofenceid" id="pkgeofenceid" />
                    <p>You will be logged out in <var id="countsecond">15</var> seconds.Do you want to stay signed in?<span></span></p>
                    <button type="type" id="btn_confirmSession_form" name="btn_confirmSession_form" class="signbtn" data-loading-text="Loading..." >Continue online</button>
                    <button type="submit" id="submitBtn_confirmSession_form" name="submitBtn_confirmSession_form" class="logoutbtn" data-loading-text="Loading..." >Sign out</button>
                </form>
            </div>
        </div>
    </div>
</div>
@push("scripts")
<script type="text/javascript">

    $("#btn_confirmSession_form").on('click', function() {
        setGetCookie();
        $('#confirmSessionModel').modal('hide');
    });

    $("#submitBtn_confirmSession_form").on('click', function() {
        deleteCookie("ethiopayCookieTimeout");
        window.location.href =  "{{ url('admin/logout') }}";
    });

</script>
@endpush