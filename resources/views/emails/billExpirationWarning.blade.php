<!DOCTYPE html>
<html lang="en">
    <head>
        <head>
            <title>{{ config('ethiopay.EMAIL.SUBJECT_PRE_TEXT') . 'Bill Expiration Warning' }}</title>
        </head>
    </head>
    <body>
        @include('emails.include.header')
            <div style="">
                    <h3 style="color:#484848; font-size:20px; margin:0; font-weight: bold;">Dear {{ $user->firstname }},</h3>
            </div>
            <div style="">
                <a href="#" style="display: inline-block;"><img src="{{ asset('img/emails/warning-img.png') }}" alt="" style="max-width:100%; margin: 37px 0 26px; width: 245px;"></a>
            </div>
            <div style="width:80%; margin: 0 auto;">
                    <h1 style="color:#484848; font-size:30px; font-weight: bold; margin:0;">Please, be Warning!</h1>
            </div>
        </div>
        <div style="margin: 28px 45px;">
            <p style="color: #484848; font-size: 14px; line-height: 25px; font-weight: regular;">You Forgot to pay bills. Fusce quis odio consectetur, interdum augue quis, faucibus mi. Pellentesque eget interdum odio.</p>
            <div style="">
                    <h5 style="margin: 60px 0 32px; color:#484848; font-size:14px; font-weight: bold;">The Ethiopay team.</h5>
            </div>
        </div>
        <div style="text-align: center;padding-top: 16px;"><a href="{{ url('pay-bill') }}" style=" font-size:14px; font-weight: bold; background-color: #93C614; border-radius: 4px; display: inline-block; padding: 16px 32px; text-decoration: none; color:white;">PAY BILLS NOW</a></div>
            <div style="margin: 28px 45px 15px;">     
                <p style="color: #484848; font-size: 14px; line-height: 25px; font-weight:regular;">If the link does not work, copy and paste the web address below into your browser to pay bill.</p>
                <p style="color: #484848; font-size: 14px; line-height: 25px; font-weight:regular;">{{ url('pay-bill') }}</p>
                {{--  <p style="color: #484848; font-size: 14px; line-height: 25px; font-weight:regular;">Lorem ipsum dolor sit amet, consectetur adipiscing elit.Curabitur nec ex rutrum, egestas enim eget</p>  --}}
            </div>
            {{-- <div style="text-align: center;"><a href="#" style=" font-size:14px; font-weight: bold; background-color: #93C614; border-radius: 4px; display: inline-block; padding: 16px 40px; text-decoration: none; color:white;">PAY BILLS NOW</a></div>
            <div style="margin: 47px 45px 0;">
                <p style="color: #484848; font-size: 14px; line-height: 25px;font-weight: regular;">Lorem ipsum dolor sit amet, consectetur adipiscing elit.Curabitur nec ex rutrum, egestas enim eget</p>
            </div> --}}
        </div>
        @include('emails.include.footer')
        @if(!isset($isWebView))
            <p style="font-size:12px; color:#484848; font-weight:regular;">Can’t read this email? <a href="{{ url('user/email/billExpirationWarning/'.\App\library\CommonFunction::encodeForID($user->id) ) }}" style="font-size:12px; color:#8BA0B3; display: inline-block; text-decoration: none;font-weight:bold;">Click here to view online</a>.</p>
        @endif
            </div>
        </div>
    </div>
    </body>
</html>