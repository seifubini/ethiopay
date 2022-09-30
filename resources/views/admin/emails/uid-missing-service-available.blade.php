<!DOCTYPE html>
<html lang="en">
    <head>
    <head>
        <title>{{ config('ethiopay.EMAIL.SUBJECT_PRE_TEXT') . 'Service Payed' }}</title>
    </head>
</head>
<body>
    @include('emails.include.header')
    <div style="">
        <h3 style="color:#484848; font-size:20px; margin:0; font-weight: bold;">Dear {{ $user->firstname }},</h3>
    </div>
    <div style="width:80%; margin: 0 auto; padding: 29px 0 0;">
        <h1 style="color:#484848; font-size:30px; font-weight: bold; margin:0;">New Service Availabe!
        </h1>
    </div>
</div>
<div style="margin: 47px 45px 0;">
    <table width="100%" height="100%" cellpaddign="10" cellspacing="0" style="border:3px solid #CFCFCF; margin: 37px 0 27px;">
        <tr style="">
            <td style="width:30%; padding:15px;color:#484848;font-size:18px; font-weight:regular;">UID</td>
            <td style="padding:15px;color:#484848;font-size:18px; font-weight: bold;">{{ $uidMissing->uid }}</td>
        </tr>
        <tr style="">
            <td style="padding:15px;color:#484848;font-size:18px; font-weight:regular;">Service Type</td>
            <td style="padding:15px;color:#484848;font-size:18px; font-weight: bold;">{{ $uidMissing->serviceTypeData->service_name }}</td>
        </tr>
    </table>
    <p style="color: #484848; font-size: 14px; line-height: 25px; font-weight:regular;">To pay your bill, click the link below.</p>            
    <div style="text-align: center;"><a href="{{ url("pay-bill") }}" style=" font-size:14px; font-weight: bold; background-color: #93C614; border-radius: 4px; display: inline-block; padding: 16px 70px; text-decoration: none; color:white;">PAY BILL</a></div>            
    <p style="color: #484848; font-size: 14px; line-height: 25px; font-weight:regular;">If the link does not work, copy and paste the web address below into your browser to view all your past service payments.</p>            
    <p style="color: #484848; font-size: 14px; line-height: 25px; font-weight:regular;">{{ url("pay-bill") }}</p>                        
</div>
<div style="margin: 28px 45px 15px;">
    <h5 style="margin: 28px 0 29px;  color:#484848; font-size:14px; font-weight:bold;">The Ethiopay team.</h5>
    {{--  <p style="color: #484848; font-size: 14px; line-height: 25px; font-weight:regular;">Lorem ipsum dolor sit amet, consectetur adipiscing elit.Curabitur nec ex rutrum, egestas enim eget</p>  --}}
</div>
@include('emails.include.footer')
    @if(!isset($isWebView))
    <p style="font-size:12px; color:#484848; font-weight:regular;">Can’t read this email? <a href="{{ url('user/email/uid-missing/'.\App\library\CommonFunction::encodeForID($user->id) .'/'. \App\library\CommonFunction::encodeForID($uidMissing->id)) }}" style="font-size:12px; color:#8BA0B3; display: inline-block; text-decoration: none;font-weight:bold;">Click here to view online</a>.</p>
    @endif
    </div>
</div>
</div>
</body>
</html>