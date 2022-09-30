@extends('admin.layout.admin')
@section('title',"View User")

@section('pageHeading')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-4">
        <h2 class="">@yield('title')</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{url('admin/dashboard')}}">Dashboard</a>
            </li>
            <li>
                <a href="{{url('admin/users')}}">{{ isset($module_name) ? $module_name : '' }}</a>
            </li>
            <li class="active">
                @yield('title')
            </li>
        </ol>
    </div>
</div>
@endsection

@section('content')
@include('admin.user.menuLinks')
<section class="userblock">
    <div class="row">
        <div class="col-lg-6">
            <div class="box-shadow">
                <div class="block-title">
                    <h2>Profile Details</h2>
                </div>
                <div class="profiledtl clearfix">
                    <div class="proimg">
                        <img src="{{ $user->profile_picture_medium }}">
                    </div>
                    <div class="user_desc">
                        <div class="user_desc_row">
                            <h3>{{ $user->fullname }}</h3>
                            <p>Member since {{ $user->joindtDisplay }}</p>
                        </div>
                        <div class="user_desc_row">
                            <h3>Spent: <span>{{ $user->transactionsAmountSum}}</span></h3>
                            <p>{{ $user->transactionsCountDisplay }} Transactions</p>
                        </div>
                        {{--  <div class="adminbtn-blk">
                            <button type="button" class="bg-btn">Transactions</button>
                            <button type="button" class="border-button">Send SMS</button>
                        </div>  --}}
                    </div> 
                </div>
                <div class="usercntdtl clearfix">
                    <div class="phblock">
                        <div class="cntrow">
                            <h4>Phone</h4>
                            <span>{{ $user->phone_number }}</span>
                        </div>
                        {{--  <div class="cntrow">
                            <h4>Mobile</h4>
                            <span>(404) 305 4544</span>
                        </div>  --}}
                    </div>
                    <div class="mailblk">
                        <div class="cntrow">
                            <h4>Email</h4>
                            <a href="javascript:void(0)">{{ $user->email }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="box-shadow">
                <div class="block-title">
                    <h2>Payment Methods</h2>
                </div>
                <div class="payment_detail">
                    <ul>
                        @foreach($payments as $payment)
                        @if($payment->method_type == 'paypal')
                        <li>
                            <div class="payment-left">
                                <h3>Paypal Account</h3>
                                <a href="javascript:void(0)">{{ $payment->paypal_email }}</a>
                            </div>
                            <div class="payment-right">
                                <span>Spent: <label>{{ $payment->transactionsAmountSum }}</label></span>
                            </div>
                        </li>
                        @endif
                        @if($payment->method_type == 'card')
                        <li>
                            <div class="payment-left">
                                {{--  <h3>HSBC USA</h3>  --}}
                                <span> Card - XXXX-{{ $payment->card_number }}</span>
                            </div>
                            <div class="payment-right">
                                <span>Spent: <label>{{ $payment->transactionsAmountSum }}</label></span>
                            </div>
                        </li>
                        @endif
                        @endforeach
                        {{--  <li>
                            <div class="payment-left">
                                <h3>Bank Of America</h3>
                                <span>Debit Account - XXXX-8041</span>
                            </div>
                            <div class="payment-right">
                                <span>Spent: <label>$422.19</label></span>
                            </div>
                        </li>  --}}
                    </ul>
                </div>
                {{--  <div class="adminbtn-blk">
                    <button type="button" class="bg-btn">Sign In</button>
                    <button type="button" class="border-button">Send SMS</button>
                </div>  --}}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box-shadow no-padding user_payment_graphblk">
                <div class="statisticblk clearfix">
                    <div class="statistic_top">
                        <h2>Overall statistics</h2>
                        <div class="graph_dayblk">
                            <button type="button" value="day" class="graph">Day</button>
                            <button type="button" value="week" class="graph">Week</button>
                            <button type="button" value="month" class="graph">Month</button>
                            <button class="active graph" type="button" value="year">Year</button>
                        </div>
                    </div>
                    <div class="statisticgraph"> 
                        <canvas id="transactionChart"></canvas>
                    </div>
                    <ul class="graphdetail clearfix">
                        {{--  @foreach($graphLables as $graphLable)
                            <li>
                                <span class="shape"></span>
                                <span>{{ $graphLable->service_name }}</span>
                        </li>
                        @endforeach  --}}
                        {{--  <li>
                            <span class="shape shape_ect"></span>
                            <span>Electicity</span>
                        </li>
                        <li>
                            <span class="shape shape_water"></span>
                            <span>Water</span>
                        </li>
                        <li>
                            <span class="shape shape_tcom"></span>
                            <span>Telecom</span>
                        </li>  --}}
                    </ul>
                </div> 
                <div class="frequentblk">
                    <div class="block-title">
                        <h2>User Frequent Payments</h2>
                    </div>
                    <ul>
                        @foreach($usersPayment as $userPayment)
                            <li>
                                <div class="frequent_userimg">{{$userPayment->totalTransaction}}</div>
                                <span>{{$userPayment->userFullname}}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div>

    </div>
</section>
@endsection

@push('scripts')
<script type="text/javascript" src="{{ asset('admins/js/demo/Chart.min.js') }}"></script>
<script type="text/javascript">
    var transactionChartLabels = [];
    var transactionChartDataSet = [];
    var transactionChart = null;
    var transactionServiceName = [];
    var transactionColor = [];
    $(".graph").click(function(){
        $(".graph").removeClass('active');
        $(this).addClass('active');
        chartAjax($(this).val());
    });
    
    chartAjax('year');
    function chartAjax(graph){
        $.ajax({
        type: "GET",
            url: "{{ url('admin/transactionChartData') }}/" + 'chartType/' + graph + '/' + "{{ $user->id }}",
            dataType: 'json',
            beforeSend: function () {

            },
            success: function (transactionChartRes) {
                transactionChartLabels = transactionChartRes.transactionChartLabels;
                transactionChartDataSet = transactionChartRes.transactionChartDataSet;
                transactionDataSet = transactionChartRes.totalDataSet;
                transactionServiceName = transactionChartRes.serviceName;
                transactionColor = transactionChartRes.color;
                $(".graphdetail").empty();
                for (var i = 0; i < transactionServiceName.length; i++) {
                    var graphdetail = "<li><span class='shape' style='background-color:" + transactionColor[i] + "'></span><span>" + transactionServiceName[i].service_name + "</span></li>";
                    $(".graphdetail").append(graphdetail);
                }
                if (transactionChart){
                    transactionChart.destroy();
                }
                renderTransactionChart();
            },
            error: function (xhr, status, error) {

            }
        });
    }

    function renderTransactionChart(){
        var transactionChartElement = document.getElementById("transactionChart");
        transactionChartElement.height = 190;
        transactionChartElement.width = 190;
        transactionChart = new Chart(transactionChartElement, {
            type: 'line',
            data: {
            labels: transactionChartLabels,
                datasets: transactionDataSet,
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 0,
                        right: 0,
                        top: 0,
                        bottom: 0
                    }
                },
                tooltips: {
                    intersect: false,
                    callbacks: {
                        {{--  title: function(tooltipItem, data) {
                            return '';
                                //return data['labels'][tooltipItem[0]['index']];
                        },  --}}
                        label: function(tooltipItem, data) {
                            return '$' + Number(tooltipItem.yLabel).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                                //return data['datasets'][0]['data'][tooltipItem['index']];
                        },
                        afterLabel: function(tooltipItem, data) {
                            return '';
                        }
                    },
                    {{--  backgroundColor: 'rgba(144, 198, 176, 1.0)',
                    titleFontSize: 16,
                    titleFontColor: '#0066ff',
                    bodyFontColor: '#000',
                    bodyFontSize: 14,
                    displayColors: false,  --}}
                },
                legend: {
                    display: false
                },
                scaleLabel: function(label){return  ' $' + label.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); },
                scales : {
                    xAxes : [{
                        gridLines : {
                            display : false
                        },
                        ticks: {
                            autoSkip: false,
                            maxRotation: 90,
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            callback: function(label, index, labels) {
                                if (Math.floor(label) === label) {
                                    return '$' + label.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                }
                            },
                            beginAtZero: true
                        },
                                        //scaleLabel: {
                                        //  display: true,
                                        //  labelString: 'probability'
                                        //}
                    }]
                },
                responsive: true,
                title: {
                    display: false,
                    text: ''
                }
            }
        });
    }
</script>
@endpush
