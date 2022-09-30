
<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8">
     <title>{{ config('ethiopay.EMAIL.SUBJECT_PRE_TEXT') . 'Monthly Service Payed' }}</title>
     <style>
            tr:nth-child(odd){background-color: #F5F5F5}
     </style>
</head>

<body>
    @include('emails.include.header')
                    <div style="">
                              <h3 style="color:#484848; font-size:20px; margin:0; font-weight: bold;">Dear {{ $user->firstname }},</h3>
                         </div>
                         <div style="width:80%; margin: 0 auto; padding: 29px 0 0;">
                              <h1 style="color:#484848; font-size:30px; font-weight: bold; margin:0;">Please, review your</h1>
                              <h1 style="color:#009245; font-size:30px; font-weight: bold; margin:0; padding-top: 12px;">Monthly Expenses Report</h1>
                         </div>
                    </div>
                    <div style="margin: 47px 45px 0;">
                         <table width="100%" height="100%" cellpaddign="10" cellspacing="0" border="0">
                              <tr style="">
                                   <td style="">
                                        <a href="#" style="display: inline-block; text-decoration: none;"><img src="{{ asset('img/emails/report-img.png') }}" alt=""></a>
                                   </td>
                                   <td style="padding-left:37px; vertical-align: top;">
                                        <p style="font-size:14px; color:#484848; line-height: 24px; margin: 0;font-weight:regular;">The Monthly Expenses Report is sent out every month and contains all your payments for the past month. Please review the summary report below for highlights.
                                            </p>
                                   </td>
                              </tr>
                         </table>
                         <table width="100%" height="100%" cellpaddign="10" cellspacing="0" style="border:3px solid #CFCFCF; margin: 37px 0 27px;">

                              <tr style="background:#CFCFCF;">
                                   <td style="padding:15px;color:#787878;font-size:18px; font-weight:regular;">TYPE</td>
                                   <td style="padding:15px;color:#787878;font-size:18px; font-weight:regular;text-align: center;">AMOUNT</td>
                                   <td style="padding:15px;color:#787878;font-size:18px; font-weight:regular;text-align: center;">SUM</td>
                              </tr>
                              <?php $sum = [] ?>
                              @foreach($transactions as $transaction)
                              <tr style="">
                                   <td style="padding:15px;color:#484848;font-size:18px; font-weight:regular;">{{ $transaction->service_name }}</td>
                                   <td style="padding:15px;color:#484848;font-size:18px; font-weight:regular;text-align: center;">{{ $transaction->totalTransaction }}</td>
                                   <td style="padding:15px;color:#484848;font-size:18px; font-weight: bold;float:right;">${{ $transaction->transactionsAmountSum }}</td>
                                    <?php $sum[] = $transaction->transactionsAmountSum ?>
                             </tr>
                              @endforeach
                              <tr style="background-color:#009245;">
                                   <td colspan="2" style="padding:15px;color:#FFF;font-size:18px; font-weight: bold;">Total:</td>
                                   <td style="padding:15px;color:#FFF;font-size:22px; font-weight:bold;float:right;">${{ number_format(array_sum($sum), 2) }}</td>
                              </tr>
                         </table>
                         <p style="color: #484848; font-size: 14px; line-height: 25px; font-weight:regular;">To view the full report, click on the link below: </p>
                    </div>
                    <div style="text-align: center;padding-top: 16px;"><a href="{{ url('transaction') }}" style=" font-size:14px; font-weight: bold; background-color: #93C614; border-radius: 4px; display: inline-block; padding: 16px 32px; text-decoration: none; color:white;">VIEW FULL REPORT</a></div>
                        <div style="margin: 28px 45px 15px;">     
                            <p style="color: #484848; font-size: 14px; line-height: 25px; font-weight:regular;">If the link does not work, copy and paste the web address below into your browser to view the full report.</p>
                         <p style="color: #484848; font-size: 14px; line-height: 25px; font-weight:regular;">{{ url('transaction') }}</p>
                         
                         <h5 style="margin: 40px 0 29px; color:#484848; font-size:14px; font-weight: bold;">The Ethiopay team.</h5>
                         {{--  <p style="color: #484848; font-size: 14px; line-height: 25px; font-weight:regular;">Lorem ipsum dolor sit amet, consectetur adipiscing elit.Curabitur nec ex rutrum, egestas enim eget</p>  --}}
                    </div>
               </div>
               @include('emails.include.footer')
               @if(!isset($isWebView))
                   <p style="font-size:12px; color:#484848; font-weight:regular;">Can’t read this email? <a href="{{ url('user/email/monthlyServicesPayed/'.\App\library\CommonFunction::encodeForID($user->id) . '/' .urlencode(\Carbon\Carbon::now('UTC')->subMonth()->startOfMonth())  . '/' .urlencode(\Carbon\Carbon::now('UTC')->subMonth()->endOfMonth()) ) }}" style="font-size:12px; color:#8BA0B3; display: inline-block; text-decoration: none;font-weight:bold;">Click here to view online</a>.</p>
               @endif
            </div>
          </div>
     </div>
</body>

</html>
