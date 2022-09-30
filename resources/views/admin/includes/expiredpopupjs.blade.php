<script type="text/javascript">

    var SESSION_EXPIRED_MINUTE_LIMIT = {!! Config("ethiopay.SESSION_EXPIRED_MINUTE_LIMIT") !!};
    var currentTime;
    var popupTime;
    var cookieSecondInterval;
    var sessionExpireTime;
    var cookieDateObject;
    var ethiopayCookieTimeoutExist;
    var ethiopayCookie;

    $(function() {
        if(ethiopayCookieTimeoutExist != -1){
            deleteCookie("ethiopayCookieTimeout");
        }
        setGetCookie();

        $('body').mousemove(function() {
            if( $("#confirmSessionModel").is(':hidden')) {
                setGetCookie();
            }
        });

        clearInterval(cookieSecondInterval);
        cookieSecondInterval = setInterval(function(){
            checkCookie();
        }, 1000);
    });

    $(window).focus(function() {
        clearInterval(cookieSecondInterval);
        cookieSecondInterval = setInterval(function(){
            checkCookie();
        }, 1000);
    });

    function setGetCookie() {
        ethiopayCookieTimeoutExist = document.cookie.indexOf('ethiopayCookieTimeout=');

        currentTime = new Date();
        popupTime = new Date(currentTime);
        popupTime.setMinutes ( currentTime.getMinutes() + SESSION_EXPIRED_MINUTE_LIMIT );

        setCookie("ethiopayCookieTimeout", popupTime , 365);
    }

    function setCookie(cookieName,cookieValue, exdays) {

        var d = new Date();
        d.setTime(d.getTime() + (exdays*24*60*60*1000));
        var expires = "expires="+ d.toUTCString();

        document.cookie = cookieName + "=" + cookieValue + ";" + expires + "; path=/";
    }

    function getCookie(cookieName) {
        var cname = cookieName + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var splitDecodedCookies = decodedCookie.split(';');
        for(var i = 0; i < splitDecodedCookies.length; i++) {
            var splitDecodedCookie = splitDecodedCookies[i];
            while (splitDecodedCookie.charAt(0) == ' ') {
                splitDecodedCookie = splitDecodedCookie.substring(1);
            }
            if (splitDecodedCookie.indexOf(cname) == 0) {
                return splitDecodedCookie.substring(cname.length, splitDecodedCookie.length);
            }
        }
        return "";
    }

    function checkCookie() {

        var currentDateTime = new Date();
        currentDateTime = currentDateTime.toString();

        ethiopayCookie = getCookie("ethiopayCookieTimeout");
        cookieDateObject = new Date(ethiopayCookie);
        cookieDateObject.setSeconds ( cookieDateObject.getSeconds() + 15 );
        sessionExpireTime = cookieDateObject.toString();
        ethiopayCookieTimeoutExist = document.cookie.indexOf('ethiopayCookieTimeout=');
        if (currentDateTime < ethiopayCookie) {
            if( $("#confirmSessionModel").is(':visible')) {
                $('#confirmSessionModel').modal('hide');
            }
        } else if (currentDateTime == ethiopayCookie || currentDateTime < sessionExpireTime) {
            if( $("#confirmSessionModel").is(':hidden')) {
                $('#confirmSessionModel').modal('show');
                $('#countsecond').timeTo({
                    seconds : 15,
                    fontSize : 18,
                    countdownAlertLimit : 0
                });

                for(i = 0; i <= 5; i++) {
                    $("#countsecond").children().eq(i).addClass('hidden');
                }
            }
        }

        if (currentDateTime >= sessionExpireTime && ethiopayCookieTimeoutExist != -1) {
            sessionExpired();
        }

        if (ethiopayCookie == '' || ethiopayCookieTimeoutExist == -1) {
            window.location.href =  "{{ url('admin/logout') }}";
        }
    }

    function deleteCookie(cookieName) {
        var expireCookieDate = new Date();
        document.cookie = cookieName + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT; path=/';
    };

    function sessionExpired() {
        $.ajax({
            type: "GET",
            url: "{{url('admin/session/sessionExpired')}}",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            success: function (data) {
                if(data.status == true) {
                    window.location.href = "{{ url('admin/login') }}";
                }
            }
        });
    }
</script>
