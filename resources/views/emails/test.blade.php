<!DOCTYPE html>
<html lang="en">
    <head>
        <head>
            <title>{{ config('ethiopay.EMAIL.SUBJECT_PRE_TEXT') . 'Registration' }}</title>
        </head>
    </head>
    <body>
        @include('emails.include.header')
            <div style="">
                    <h3 style="color:#484848; font-size:20px; margin:0; font-weight: bold;">Dear Payor,</h3>
               </div>
               <div style="">
                    <a href="#" style="display: inline-block;"><img src="{{ asset('img/emails/confirm-img.svg') }}" alt="" style="max-width:100%; margin: 37px 0 26px; width: 245px;"></a>
               </div>
               <div style="">
                    <h1 style="color:#484848; font-size:30px; font-weight: bold; margin:0;">Confirm your email address</h1>
               </div>
            </div>
            <div style="margin: 28px 45px;">
                <p style="color: #484848; font-size: 14px; line-height: 25px; font-weight:regular;">Lorem ipsum dolor sit amet, consectetur adipiscing elit.Curabitur nec ex rutrum, egestas enim eget</p>
                <div style="">
                    <h5 style="margin: 60px 0 32px; color:#484848; font-size:14px; font-weight: bold;">The Ethiopay team.</h5>
                </div>
            </div>
            <div style="text-align: center;"><a href="{{ url("activateAccountByEmail/".\App\library\CommonFunction::encodeForID($user->id)) }}" style=" font-size:14px; font-weight: bold; background-color: #93C614; border-radius: 4px; display: inline-block; padding: 16px 70px; text-decoration: none; color:white;">CONFIRM</a></div>
            <div style="margin: 47px 45px 0;">
                <p style="color: #484848; font-size: 14px; line-height: 25px; font-weight:regular;">Lorem ipsum dolor sit amet, consectetur adipiscing elit.Curabitur nec ex rutrum, egestas enim eget</p>
            </div>
        @include('emails.include.footer')
    </body>
</html>