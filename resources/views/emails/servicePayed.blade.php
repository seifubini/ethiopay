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
                    <h1 style="color:#484848; font-size:30px; font-weight: bold; margin:0;">Your service payment was successful!
                        </h1>
            </div>
        </div>
        <div style="margin: 47px 45px 0;">
            <table width="100%" height="100%" cellpaddign="10" cellspacing="0" border="0">
                    <tr style="">
                        <td style="">
                            <a href="#" style="display: inline-block; text-decoration: none;"><img src="{{ asset('img/emails/dollar.png') }}" alt=""></a>
                        </td>
                        <td style="padding-left:37px; vertical-align: top;">
                            <p style="font-size:14px; color:#484848; line-height: 24px; margin: 0;font-weight:regular;">We are pleased to notify you that the service payment you initiated was successful. Please review the service payment details below.  
                            </p>
                        </td>
                    </tr>
            </table>
            <table width="100%" height="100%" cellpaddign="10" cellspacing="0" style="border:3px solid #CFCFCF; margin: 37px 0 27px;">
                <tr style="">
                    <td style="width:30%; padding:15px;color:#484848;font-size:18px; font-weight:regular;">Invoice Paid, #</td>
                    <td style="padding:15px;color:#484848;font-size:18px; font-weight: bold;">{{ $transaction->random_transaction_id }}</td>
                </tr>
                <tr style="background-color:#F5F5F5;">
                    <td style="padding:15px;color:#484848;font-size:18px; font-weight:regular;">Debter</td>
                    <td style="padding:15px;color:#484848;font-size:18px; font-weight: bold;">{{ $transaction->customer_service_number }}</td>
                </tr>
                <tr style="">
                    <td style="padding:15px;color:#484848;font-size:18px; font-weight:regular;">Purpose </td>
                    <td style="padding:15px;color:#484848;font-size:18px; font-weight: bold;">{{ $serviceProvider->provider_name }} Service in {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $transaction->created_at, 'UTC')->setTimezone(config('ethiopay.TIMEZONE_STR'))->format('F Y') }}</td>
                </tr>
                <tr style="background-color:#009245;">
                    <td style="padding:15px;color:#FFF;font-size:18px; font-weight: bold;">Sum Paid</td>
                    <td style="padding:15px;color:#FFF;font-size:22px; font-weight:bold;">${{ $transaction->total_pay_amount }}</td>
                </tr>
            </table>
            <p style="color: #484848; font-size: 14px; line-height: 25px; font-weight:regular;">If you do not want to receive receipts after each payment, click <a href="#" style="font-size:12px; color:#8BA0B3; display: inline-block; text-decoration: none;font-weight:bold;">here</a>.</p>
            <p style="color: #484848; font-size: 14px; line-height: 25px; font-weight:regular;">If you do not wish to continue receiving service payment notifications via email click here.To view all your past service payments, click the link below.</p>            
            <div style="text-align: center;"><a href="{{ url("transaction") }}" style=" font-size:14px; font-weight: bold; background-color: #93C614; border-radius: 4px; display: inline-block; padding: 16px 70px; text-decoration: none; color:white;">SERVICE PAYMENTS</a></div>            
            <p style="color: #484848; font-size: 14px; line-height: 25px; font-weight:regular;">If the link does not work, copy and paste the web address below into your browser to view all your past service payments.</p>            
            <p style="color: #484848; font-size: 14px; line-height: 25px; font-weight:regular;">{{ url("transaction") }}</p>                        
        </div>
        <div style="margin: 28px 45px 15px;">
            <h5 style="margin: 28px 0 29px;  color:#484848; font-size:14px; font-weight:bold;">The Ethiopay team.</h5>
            {{--  <p style="color: #484848; font-size: 14px; line-height: 25px; font-weight:regular;">Lorem ipsum dolor sit amet, consectetur adipiscing elit.Curabitur nec ex rutrum, egestas enim eget</p>  --}}
        </div>
        @include('emails.include.footer')
        @if(!isset($isWebView))
            <p style="font-size:12px; color:#484848; font-weight:regular;">Can’t read this email? <a href="{{ url('user/email/paybill/'.\App\library\CommonFunction::encodeForID($user->id) .'/'. \App\library\CommonFunction::encodeForID($transaction->id)) }}" style="font-size:12px; color:#8BA0B3; display: inline-block; text-decoration: none;font-weight:bold;">Click here to view online</a>.</p>
        @endif
            </div>
        </div>
    </div>
    </body>
</html>