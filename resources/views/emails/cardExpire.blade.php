
<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8">
     <title>{{ config('ethiopay.EMAIL.SUBJECT_PRE_TEXT') . 'Card Expire Warning' }}</title>
</head>

<body>
    @include('emails.include.header')
                         <div style="">
                              <a href="#" style="display: inline-block;"><img src="{{ asset('img/emails/card.png') }}" alt="" style="max-width:100%; margin: 37px 0 26px; width: 245px;"></a>
                         </div>
                         <div style="width:80%; margin: 0 auto;">
                              <h1 style="color:#484848; font-size:30px; font-weight: bold; margin:0;">Warning! Your Credit Card is about to expire!</h1>
                         </div>
                    </div>
                    <div style="margin: 47px 45px 0;">
                        {{-- <table width="100%" height="100%" cellpaddign="10" cellspacing="0" style="border:3px solid #CFCFCF; margin: 37px 0 27px;">
                            <tr style="">
                                <td style="width:30%; padding:15px;color:#484848;font-size:18px; font-weight:regular;">Card Holder Name</td>
                                <td style="padding:15px;color:#484848;font-size:18px; font-weight: bold;">{{ $paymentMethod['name_on_card'] }}</td>
                            </tr>
                            <tr style="background-color:#F5F5F5;">
                                <td style="padding:15px;color:#484848;font-size:18px; font-weight:regular;">Card Number</td>
                                <td style="padding:15px;color:#484848;font-size:18px; font-weight: bold;">{{ $paymentMethod['card_number'] }} <img id="transactionCardImage" src="{{ $paymentMethod['payment_method_icon'] }}" style="height:17px;"></td>
                            </tr>
                            <tr style="">
                                <td style="padding:15px;color:#484848;font-size:18px; font-weight:regular;">Expiry Date</td>                               
                                <td style="padding:15px;color:#484848;font-size:18px; font-weight: bold;">{{ Carbon\Carbon::createFromFormat('Y-m', $paymentMethod['card_expiry_year'] .'-'. $paymentMethod['card_expiry_month'])->format('m / Y') }} </td>
                            </tr>
                        </table> --}}
                        <p style="color: #484848; font-size: 14px; line-height: 25px; font-weight:regular;">You Forgot to pay bills. Fusce quis odio consectetur, interdum augue quis, faubicus mi. Pellentesque eget interdum odio.</p>                        
                        <p style="color: #484848; font-size: 14px; line-height: 25px; font-weight:regular;">To view all your card, click the link below.</p>            
                        <div style="text-align: center;"><a href="{{ url("payment-methods") }}" style=" font-size:14px; font-weight: bold; background-color: #93C614; border-radius: 4px; display: inline-block; padding: 16px 70px; text-decoration: none; color:white;">WALLET</a></div>            
                        <p style="color: #484848; font-size: 14px; line-height: 25px; font-weight:regular;">If the link does not work, copy and paste the web address below into your browser to view all your card.</p>            
                        <p style="color: #484848; font-size: 14px; line-height: 25px; font-weight:regular;">{{ url("payment-methods") }}</p>                        
                    </div>
                    <div style="margin: 28px 45px 15px;">
                        <h5 style="margin: 28px 0 29px;  color:#484848; font-size:14px; font-weight:bold;">The Ethiopay team.</h5>
                        {{--  <p style="color: #484848; font-size: 14px; line-height: 25px; font-weight:regular;">Lorem ipsum dolor sit amet, consectetur adipiscing elit.Curabitur nec ex rutrum, egestas enim eget</p>  --}}
                    </div>
               </div>
               @include('emails.include.footer')
               @if(!isset($isWebView))
                   <p style="font-size:12px; color:#484848; font-weight:regular;">Can’t read this email? <a href="{{ url('user/email/cardExpire/'.\App\library\CommonFunction::encodeForID($user->id) . '/' . \App\library\CommonFunction::encodeForID($paymentMethod['id']) ) }}" style="font-size:12px; color:#8BA0B3; display: inline-block; text-decoration: none;font-weight:bold;">Click here to view online</a>.</p>
               @endif
            </div>
          </div>
     </div>
</body>

</html>
