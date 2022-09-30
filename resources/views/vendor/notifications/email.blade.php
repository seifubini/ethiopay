<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8">
     <title>{{ config('ethiopay.EMAIL.SUBJECT_PRE_TEXT') . 'Reset Password' }}</title>
</head>

<body>
    @include('emails.include.header')
            <div style="">
                <h3 style="color:#484848; font-size:20px; margin:0;font-weight:regular;">Dear {{ $outroLines[1] }},</h3>
            </div>
            <div style="">
                <a href="#" style="display: inline-block;"><img src="{{ asset('img/emails/password.png')}}" alt="" style="max-width:100%; margin: 37px 0 26px; width: 280px;"></a>
            </div>
            <div style="">
                <h1 style="color:#484848; font-size:30px; font-weight:regular; margin:0;">Forgot your password?</h1>
            </div>
        </div>
            <div style="margin: 25px 45px 28px;">
                    <p style="color: #484848; font-size: 14px; line-height: 25px;font-weight:regular;">Don’t worry, that happens sometimes. To rest your password, click on the link below. </p>
            </div>
            <div style="text-align: center;"><a href="{{ $actionUrl }}" style=" font-size:14px; font-weight: bold; background-color: #93C614; border-radius: 4px; display: inline-block; padding: 16px 30px; text-decoration: none; color:white;">RESET YOUR PASSWORD</a></div>
            <div style=" margin: 29px 45px 0;">
                    <p style="color: #484848; font-size: 14px; line-height: 25px;font-weight:regular;">If the link does not work, copy and paste the web address below into your browser to reset your password.
                    </p>
                    <p style="color: #484848; font-size: 14px; line-height: 25px;font-weight:regular;word-break: break-all;">{{ $actionUrl }}</p>
                    <p style="color: #484848; font-size: 14px; line-height: 25px;font-weight:regular;">If you did not mean to change your password simply ignore this email and nothing will happen. Your password will remain unchanged.
                    </p>
                    <div style="">
                        <h5 style="margin: 27px 0 22px; color:#484848; font-size:14px; font-weight:bold;">The Ethiopay team.</h5>
                    </div>
            </div>
        </div>
        <div style="padding: 22px 10px;">
            <p style="font-size:12px; color:#484848; font-weight:regular;">Powered by <a href="#" style="font-size:12px; color:#8BA0B3; display: inline-block; text-decoration: none; font-weight: bold;">Ethiopay</a></p>
            <p style="font-size:12px; color:#484848;line-height: 20px;font-weight:regular;">This email has been sent to <a href="" style="font-size:12px; color:#8BA0B3; display: inline-block; text-decoration: none; font-weight: bold;">{{ $outroLines[0] }}</a>, and it is srictky confidential. Do not forward it.<a href="#" style="font-size:12px; color:#8BA0B3; display: inline-block; text-decoration: none; font-weight: bold;">Unsubscribe</a> from mail notification list.</p>
            @if( $outroLines[3] == 'user')    
                @if(!isset($isWebView))
                    <p style="font-size:12px; color:#484848; font-weight:regular;">Can’t read this email? <a href="{{ url('user/email/forgotPassword/'.\App\library\CommonFunction::encodeForID($outroLines[2]).'/'. $outroLines[4]) }}" style="font-size:12px; color:#8BA0B3; display: inline-block; text-decoration: none;font-weight:bold;">Click here to view online</a>.</p>
                @endif
            @else
                @if(!isset($isWebView))
                    <p style="font-size:12px; color:#484848; font-weight:regular;">Can’t read this email? <a href="{{ url('admin/email/forgotPassword/'.\App\library\CommonFunction::encodeForID($outroLines[2]).'/'. $outroLines[4]) }}" style="font-size:12px; color:#8BA0B3; display: inline-block; text-decoration: none;font-weight:bold;">Click here to view online</a>.</p>
                @endif
            @endif
            {{--  <p style="font-size:12px; color:#484848; font-weight:regular;">Can’t read this email? <a href="#" style="font-size:12px; color:#8BA0B3; display: inline-block; text-decoration: none;font-weight:bold;">Click here to view online</a>.</p>  --}}
        </div>
        </div>
        </div>
</body>

</html>
