<!DOCTYPE html>
<html lang="en">
    <head>
        <head>
            <title>{{ config('ethiopay.EMAIL.SUBJECT_PRE_TEXT') . 'Welcome' }}</title>
        </head>
    </head>
    <body>
        @include('emails.include.header')
            <div style="">
                    <h3 style="color:#484848; font-size:20px; margin:0; font-weight: bold;">Dear {{ $user->firstname }},</h3>
               </div>
               <div style="">
                    <a href="#" style="display: inline-block;"><img src="{{ asset('img/emails/Email-img.png') }}" alt="" style="max-width:100%; margin: 37px 0 26px; width: 245px;"></a>
               </div>
               <div style="">
                    <h1 style="color:#484848; font-size:30px; font-weight: bold; margin:0;">Welcome to EthioPay!</h1>
               </div>
            </div>
            <div style="margin: 28px 45px;">
                <p style="color: #484848; font-size: 14px; line-height: 25px; font-weight:regular;">You are now on your way to stress-free, reliable and super secure payments. With EthioPay, you will be able to connect with your loved ones back home like never before. </p>
                <p style="color: #484848; font-size: 14px; line-height: 25px; font-weight:regular;">Our seamless remit-to-pay services will make it as easy as ever to assist your loved ones by paying those critical bills that touch their lives. To get started, click the button below to accept our <a href="{{ route('termsAndConditions') }}">terms and conditions.</a> </p>
                
                <div style="">
                    <h5 style="margin: 60px 0 32px; color:#484848; font-size:14px; font-weight: bold;">The Ethiopay team.</h5>
                </div>
            </div>
            {{--  <div style="margin: 47px 45px 0;">
                <p style="color: #484848; font-size: 14px; line-height: 25px; font-weight:regular;">If the link does not work, copy and paste the web address below into your browser to accept our terms and conditions.</p>
            </div>  --}}
        </div>
        @include('emails.include.footer')
        @if(!isset($isWebView))
            <p style="font-size:12px; color:#484848; font-weight:regular;">Can’t read this email? <a href="{{ url('user/email/welcome/'.\App\library\CommonFunction::encodeForID($user->id) ) }}" style="font-size:12px; color:#8BA0B3; display: inline-block; text-decoration: none;font-weight:bold;">Click here to view online</a>.</p>
        @endif
            </div>
        </div>
    </div>
    </body>
</html>