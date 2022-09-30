<!DOCTYPE html>
<html lang="en">
    <head>
    <head>
        <title>{{ config('ethiopay.EMAIL.SUBJECT_PRE_TEXT') . $emailMessage->title }}</title>
    </head>
</head>
<body>
    @include('emails.include.header')
    {{-- <div style="margin: 47px 45px 0;">
        <h3 style="color:#484848; font-size:20px; margin:0; font-weight: bold;">Dear {{ $user->firstname . ' ' . $user->lastname}},</h3>
    </div> --}}
    {{-- <div style="width:80%; margin: 0 auto; padding: 29px 0 0;">
        <h1 style="color:#484848; font-size:30px; font-weight: bold; margin:0;">{{ $emailMessage->title }}
        </h1>
    </div> --}}
</div>
<div style="margin: 47px 45px 0;">
    <p style="color: #484848; font-size: 14px; line-height: 25px; font-weight:regular;">Dear {{ $user->firstname . ' ' . $user->lastname}},</p>            
</div>
<div style="margin: 47px 45px 0;">
    <p style="color: #484848; font-size: 14px; line-height: 25px; font-weight:regular;">{!! nl2br($emailMessage->description) !!}</p>            
</div>
<div style="margin: 28px 45px 15px;">
    <h5 style="margin: 28px 0 29px;  color:#484848; font-size:14px; font-weight:bold;">The Ethiopay team.</h5>
    {{--  <p style="color: #484848; font-size: 14px; line-height: 25px; font-weight:regular;">Lorem ipsum dolor sit amet, consectetur adipiscing elit.Curabitur nec ex rutrum, egestas enim eget</p>  --}}
</div>
</div>
@include('emails.include.footer')
    @if(!isset($isWebView))
    <p style="font-size:12px; color:#484848; font-weight:regular;">Can’t read this email? <a href="{{ url('user/email/email-message/'.\App\library\CommonFunction::encodeForID($user->id) .'/'. \App\library\CommonFunction::encodeForID($emailMessage->id)) }}" style="font-size:12px; color:#8BA0B3; display: inline-block; text-decoration: none;font-weight:bold;">Click here to view online</a>.</p>
    @endif
    </div>
</div>
</div>
</body>
</html>