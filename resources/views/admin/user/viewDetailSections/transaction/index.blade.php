@extends('admin.layout.admin')

@push('styles')
<link href="{{ asset('plugins/dataTables/dataTables.bootstrap.css') }}" rel="stylesheet">
<link href="{{ asset('plugins/dataTables/dataTables.responsive.css') }}" rel="stylesheet">
<link href="{{ asset('plugins/dataTables/dataTables.tableTools.min.css') }}" rel="stylesheet">
{{-- <link rel="stylesheet" type="text/css" href="{{ asset('plugins/datepicker/datepicker3.css') }}"> --}}
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">


<style>
    .labelblk{
        margin-bottom:15px;
    }
    .labelblk label {
        float: left;
        margin: 6px 20px 0 0;
        width: 40px;
    }
    .labelblk input {
        float: left;
        width: calc(100% - 60px);
    }
    #tabmenu > li > a {
        color: black;
    }
</style>

@endpush

@section("title", "Transaction History")
@section('pageHeading')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-12">
        <h2>@yield('title')</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{url('admin/dashboard')}}">Dashboard</a>
            </li>
            <li>
                <a href="{{ url("admin/users") }}">Users</a>
            </li>
            <li>
                <a href="{{ url("admin/users/$user->id") }}">{{ $user->fullname }}</a>
            </li>
            <li>
                @yield('title')
            </li>
        </ol>
    </div>
</div>
@endsection

@section('content')
@include('admin.user.menuLinks')

<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5> @yield('title')</h5>
                </div>
                <div class="ibox-content">
                    <div class="row labelblk">
                        <div id="reportrange" class="pull-center rangeblk">
                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                            <span name="date" id="date-range" class="rangespan">Select Date Range</span> <b class="caret"></b><span class="clear-date"><span>
                        </div>
                        <!-- <div class="col-md-3" class="pageDropDownlength"> -->
                        <!-- </div> -->
                        {{-- <div class="col-md-6" class="from_date">
                            <label>From</label> <input type='text' class="form-control from"  id="from_date"/> 
                        </div>
                        <div class="col-md-6">
                            <label>To</label> <input type='text' class="form-control from"  id="to_date"/>
                        </div> --}}
                    </div>
                    <table id="transactionTable" class="table table-striped table-bordered table-hover" width="100%">
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

                        </tbody>
                        <tfoot>
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
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@include('admin.includes.viewModel')
@endsection

@push('scripts')

{{-- Data table --}}
<script src="{{ asset('plugins/dataTables/jquery.dataTables.js') }}"></script>
<script src="{{ asset('plugins/dataTables/dataTables.bootstrap.js') }}"></script>
<script src="{{ asset('plugins/dataTables/dataTables.responsive.js') }}"></script>
<script src="{{ asset('plugins/dataTables/dataTables.tableTools.min.js') }}"></script>
{{-- <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script> --}}
<script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>

<script type="text/javascript">

    $(document).ready(function() {
        var transactionTable = $('#transactionTable').DataTable({
                {{--  dom: 'rtip',  --}}
                processing: true,
                serverSide: true,
                responsive: true,
                "ajax": {
                    "url": "{{ route('admin.users.transactions.datatable', ['id' => $user->id]) }}",
                    "data": function (d) {
                        {{-- d.from_date = $("#from_date").val();
                        d.to_date = $("#to_date").val(); --}}
                        if($('#date-range').html() != "Select Date Range"){
                            d.startingDate = $('#reportrange').data('daterangepicker').startDate.format('YYYY-MM-DD');
                            d.endDate = $('#reportrange').data('daterangepicker').endDate.format('YYYY-MM-DD'); 
                        }
                    }
                },
                columns: [
                    { data: 'random_transaction_id', name: "random_transaction_id" },
                    { data: 'date', name: 'created_at'},
                    { data: 'provider_name', name: 'provider_name'},
                    { data: 'amount', name: 'amount'},  
                    { data: 'fullname', name: 'fullname'}, 
                    { data: 'debtorFullname', name: 'debtorFullname'},                 
                    {
                        data:  null,
                        name: 'status',
                        "render":function(o){
                            if(o.status == 'FAIL') {
                                return "<span class='red-txt'>FAIL</span>";
                            }
                            return "COMPLETE";
                        }
                    },
                    {
                        data:  null,
                        "orderable": false,
                        "searchable": false,
                        "render":function(o){
                        return "<a href='{{ url('admin/users') }}/" + {{ $user->id }} + "/transactions/" + o.id + "' class='text-info viewRecord'><i class='fa fa-eye'></i></a>";
                        }
                    },
                ],
                fnInitComplete : function( oSettings, json ){
                    $(".from_date").detach().appendTo("#transactionTable_filter");
                }
        });
        $('#reportrange').daterangepicker();
        var start = moment().startOf('month');
        var end = moment().endOf('month');

        function cb(start, end) {
            $('#reportrange #date-range').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            $(".clear-date").html("&#10060;");
        }
        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'This Year': [moment().startOf('year'),moment().endOf('year')],
                'Last Year': [moment().subtract(1, 'year').startOf('year'),moment().subtract(1, 'year').endOf('year')]
            }
        }, cb);

        $('#reportrange').on('apply.daterangepicker', function(ev) {
            $(".clear-date").css('display', 'inline-block');
            transactionTable.draw();
        });

        $(".clear-date").click(function(){
            $('#reportrange #date-range').html("Select Date Range");
            $(this).html("");
        });
    
        $("#to_date").datepicker({
            "format":"dd / M / yyyy",
        });
        $("#from_date").datepicker({
            "format":"dd / M / yyyy",
        });
    
        $("#from_date").on('change', function(){
            transactionTable.draw();
        });
        $("#to_date").on('change', function(){
            transactionTable.draw();
        });
    });
</script>
@endpush