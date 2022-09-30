@extends('layouts.app')
@section("title", "Home")

@section('content')
<main>
    <section class="main-123 container">
        <div class="row">
            <div class="col-md-5">
                <div class="automate_block">
                    <div class="automate_content">
                        <h2>Automate your Payments</h2>
                        <p>Use our one-time payment system or set an ongoing schedule</p>
                        <a href="#"></a>
                    </div>
                    <p class="last-para"><a href="{{ route('payment-methods.index') }}">Manage your payment methods</a> <a href="javascript:void(0)" class="contact-us">Contact us</a></p>
                </div>
            </div>
            <div class="col-md-7">
                <div class="automate_block clearfix">
                    <form action="">
                        <a class="mywallet" href="{{ route('payment-methods.index') }}" >My Wallet</a>
                        <a class="paybills" href="{{ url('pay-bill') }}" >Pay Bills</a>
<!--                        <div class="dropdown paybill"><a class="dropdown-toggle " data-toggle="dropdown" href="javascript:void(0)">Pay Bills <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                @foreach ($serviceTypes as $serviceType)
                                <li><a href="{{ url("pay-bill/service-type/".$serviceType->id) }}">{{ $serviceType->service_name }}</a></li>
                                @endforeach
                            </ul>
                        </div>-->
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="records">
                    <div class="record_table">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>User</th>
                                    <th>Debtor</th>                                    
                                    <th>Status</th>
                                    <th>Option</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->random_transaction_id }}</td>
                                    <td>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $transaction->created_at, 'UTC')->setTimezone(config('ethiopay.TIMEZONE_STR'))->format('d / M') }}</td>
                                    <td>{{ $transaction->serviceProviderData->provider_name }}</td>
                                    <td>${{ number_format($transaction->total_pay_amount, 2) }}</td>
                                    <td>{{ auth()->guard('web')->user()->fullname }}</td>
                                    <td>{{ $transaction->debtor_firstname }} {{ $transaction->debtor_lastname }}</td>
                                    @if($transaction->transaction_status == 'failed')
                                        <td class="red-txt">FAIL</td>
                                    @else 
                                        <td class="green-txt">COMPLETE</td>
                                    @endif
                                    <td><a href='{{ url('home/transaction') }}/{{ $transaction->id }}'>VIEW DETAILS</a></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($transactionsCount > 10)
                        <a href="javascript:void(0)" class="view-transaction">VIEW ALL TRANSACTIONS</a> 
                    @endif
                </div>
            </div>
        </div>
    </section>
    <section class="icon_section">
        <section class="ep_main_section">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="icon_btn">
                            <a href="{{ url("pay-bill/service-type/".env('PAYBILL_SERVICE_TYPE_UTILITY_BILLS_ID')) }}" class="icon_list"> 
                                <img src="img/bill.svg" alt="">
                                <h4>UTILITY BILLS</h4> 
                            </a>
                            <a href="{{ url("pay-bill/service-type/".env('PAYBILL_SERVICE_TYPE_HEALTH_INSURANCE_ID')) }}" class="icon_list"> 
                                <img src="img/insurance.svg" alt="">
                                <h4>HEALTH INSURANCE</h4>  
                            </a>
                            <a href="{{ url("pay-bill/service-type/".env('PAYBILL_SERVICE_TYPE_SCHOOL_FEES_ID')) }}" class="icon_list"> 
                                <img src="img/fees.svg" alt="">
                                <h4>SCHOOL FEES</h4>  
                            </a>
                            <a href="{{ url("pay-bill/service-type/".env('PAYBILL_SERVICE_TYPE_PAY_EDIR_ID')) }}" class="icon_list"> 
                                <img src="img/telecom.svg" alt="">
                                <h4>PAY TELECOM</h4>  
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </section>
    <section class="main-bottom clearfix">
        <div class="container "> <i class="automate_setting_logo"></i>
            <div class="automate_setting_content">
                <div class="st-left">
                    <h2>Automate your payments</h2>
                    <p>Eliminate unnecessary data entry that wastes your time, so you can spend hours on more complex tasks with one-click payments without the need to connect to your bank.</p>
                </div>
                <div class="sts-right"><a href="#" class="btn-primary">Learn More</a></div>
            </div>
        </div>
    </section>
</main>
@endsection
