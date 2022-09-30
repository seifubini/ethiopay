 @extends('admin.layout.admin') @push('styles') @endpush @section('title',"Dashboard") @section('pageHeading')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-4">
        <h2>@yield('title')</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{url('admin/dashboard')}}">Dashboard</a>
            </li>
        </ol>
    </div>
</div>

@endsection @section('content')
<div class="wrapper wrapper-content">
    <div class="row ethio-transcations">
        <div class="col-lg-3 col-md-6 t_blk">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Today Transactions</h5>
                </div>
                <div class="ibox-content ethio-transcation-block">
                    <img src="{{asset('admins/images/transcation-card.png')}}" class="card-image">
                    <h4>{{ $todayTransaction->todayTransaction }}</h4>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 t_blk">
            <div class="ibox withbox">
                <div class="ibox-title">
                    <h5>Total Transactions</h5>
                </div>
                <div class="ibox-content ethio-transcation-block">
                    <img src="{{asset('admins/images/transcation-card.png')}}" class="card-image">
                    <h4>{{ $totalTransaction->totalTransaction}}</h4>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 t_blk">
            <div class="ibox withbox">
                <div class="ibox-title">
                    <h5>Today Customers</h5>
                </div>
                <div class="ibox-content ethio-transcation-block">
                    <h4>{{ $todayUser->todayUser}}</h4>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 t_blk">
            <div class="ibox withbox">
                <div class="ibox-title">
                    <h5>Total Customers</h5>
                </div>
                <div class="ibox-content ethio-transcation-block">
                    <h4>{{ $totalUser->totalUser}}</h4>
                </div>
            </div>

        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="row">
                <div class="box-shadow no-padding user_payment_graphblk">
                    <div class="full_statisticblk clearfix">
                        <div class="statistic_top">
                            <h2>Transactions Amount</h2>
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
                        </ul>
                    </div>
                </div>
                <div class="box-shadow no-padding user_payment_graphblk">
                    <div class="full_statisticblk clearfix">
                        <div class="statistic_top">
                            <h2>New Customers</h2>
                            <div class="graph_dayblk">
                                <button type="button" value="day" class="customersGraph">Day</button>
                                <button type="button" value="week" class="customersGraph">Week</button>
                                <button type="button" value="month" class="customersGraph">Month</button>
                                <button class="active customersGraph" type="button" value="year">Year</button>
                            </div>
                        </div>
                        <div class="statisticgraph">
                            <canvas id="newCustomersChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection @push('scripts')
<script type="text/javascript" src="{{ asset('admins/js/demo/Chart.min.js') }}"></script>
<script type="text/javascript">
    var transactionChartLabels = [];
    var transactionChartDataSet = [];
    var transactionChart = null;
    var transactionServiceName = [];
    var transactionColor = [];
    $(".graph").click(function () {
        $(".graph").removeClass('active');
        $(this).addClass('active');
        chartAjax($(this).val());
    });

    chartAjax('year');

    function chartAjax(graph) {
        $.ajax({
            type: "GET",
            url: "{{ url('admin/dashboard/averageTrnsactionChartData') }}/" + 'chartType/' + graph,
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
                    var graphdetail = "<li><span class='shape' style='background-color:" + transactionColor[
                            i] + "'></span><span>" + transactionServiceName[i].service_name +
                        "</span></li>";
                    $(".graphdetail").append(graphdetail);
                }
                if (transactionChart) {
                    transactionChart.destroy();
                }
                renderTransactionChart();
            },
            error: function (xhr, status, error) {

            }
        });
    }

    function renderTransactionChart() {
        var transactionChartElement = document.getElementById("transactionChart");
        transactionChartElement.height = 200;
        transactionChartElement.width = 200;
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
                    mode: 'index',
                    hover: 'nearest',
                    {{--  axis: 'x',  --}}
                    intersect: false,
                    callbacks: {
                        label: function (tooltipItem, data) {
                            return '$' + Number(tooltipItem.yLabel).toString().replace(
                                /\B(?=(\d{3})+(?!\d))/g, ",");

                        },
                        afterLabel: function (tooltipItem, data) {
                            return '';
                        }
                    },
                },
                legend: {
                    display: false
                },
                scaleLabel: function (label) {
                    return ' $' + label.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                },
                scales: {
                    xAxes: [{
                        gridLines: {
                            display: false
                        },
                        ticks: {
                            autoSkip: false,
                            maxRotation: 90,
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            callback: function (label, index, labels) {
                                if (Math.floor(label) === label) {
                                    return '$' + label.toString().replace(/\B(?=(\d{3})+(?!\d))/g,
                                        ",");
                                }
                            },
                            beginAtZero: true
                        },
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

    var newCustomersChartLabels = [];
    var newCustomersChartDataSet = [];
    var newCustomersChartTypeLabel = '';
    var newCustomersChart = null;

    $(".customersGraph").click(function () {
        $(".customersGraph").removeClass('active');
        $(this).addClass('active');
        newCustomersChartAjax($(this).val());
    });

    newCustomersChartAjax('year');

    function newCustomersChartAjax(newCustomersGraph) {
        $('#newCustomersChartTypeLabel').html(newCustomersChartTypeLabel);
        $.ajax({
            type: "GET",
            url: "{{ url('admin/dashboard/newCustomersChartData') }}/" + 'chartType/' + newCustomersGraph,
            dataType: 'json',
            beforeSend: function () {

            },
            success: function (newCustomersChartRes) {
                newCustomersChartLabels = newCustomersChartRes.newCustomersChartLabels;
                newCustomersChartDataSet = newCustomersChartRes.newCustomersChartDataSet;
                if (newCustomersChart) {
                    newCustomersChart.destroy();
                }
                renderNewCustomersChart();
            },
            error: function (xhr, status, error) {

            }
        });
    }

    function renderNewCustomersChart() {
        var transactionChartElement = document.getElementById("newCustomersChart");
        transactionChartElement.height = 200;
        transactionChartElement.width = 200;
        newCustomersChart = new Chart($("#newCustomersChart"), {
            type: 'line',
            fillOpacity: 0.3,
            data: {
                labels: newCustomersChartLabels,
                datasets: [{
                    data: newCustomersChartDataSet,
                    backgroundColor: "rgba(25, 118, 57, 1)",
                    borderColor: "rgba(25, 118, 57, 1)",
                    borderWidth: 1.5,
                    pointRadius: 1,
                    fill: false,
                }]
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
                    mode: "x-axis",
                    intersect: false,
                    callbacks: {
                        label: function (tooltipItem, data) {
                            return Number(tooltipItem.yLabel).toString().replace(/\B(?=(\d{3})+(?!\d))/g,
                                ",");
                        },
                        afterLabel: function (tooltipItem, data) {
                            return '';
                        }
                    },
                },
                legend: {
                    display: false
                },
                scaleLabel: function (label) {
                    return label.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                },
                scales: {
                    xAxes: [{
                        gridLines: {
                            display: false
                        },
                        ticks: {
                            autoSkip: false,
                            maxRotation: 90,
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            callback: function (label, index, labels) {
                                if (Math.floor(label) === label) {
                                    return label.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                }
                            },
                            beginAtZero: true
                        },
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