@extends('layouts.app')
@push('styles')
    <link href="{{ asset('plugins/dataTables/dataTables.bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/dataTables/dataTables.responsive.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/dataTables/dataTables.tableTools.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/datepicker/datepicker3.css') }}">
@endpush
@section("title", "Home")

@section('content')
<main>
    <section class="transactions">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="row calendar">
                        <label for="" class="col-md-3">Transaction</label>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class='input-group date' id='datetimepicker1'>
                                            <label>From</label> <input type='text' class="form-control from"  id="from_date"/> <span class="input-group-addon">
                    <span class=" "></span> </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class='input-group date' id='datetimepicker2'>
                                            <label>To</label>
                                            <input type='text' class="form-control to" id="to_date"/> <span class="input-group-addon">
                    <span class=""></span> </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-6" id="transactionSearch">
                    <div class="search_block">
                        <input type="text" class="serach form-control" placeholder="Search">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="records">
                        <div class="record_table">
                            <table class="table table-striped" id="transactionTable">
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
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@push('scripts')

{{-- Data table --}}
<script src="{{ asset('plugins/dataTables/jquery.dataTables.js') }}"></script>
<script src="{{ asset('plugins/dataTables/dataTables.bootstrap.js') }}"></script>
<script src="{{ asset('plugins/dataTables/dataTables.responsive.js') }}"></script>
<script src="{{ asset('plugins/dataTables/dataTables.tableTools.min.js') }}"></script>
<script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>

<script type="text/javascript">

$(document).ready(function() {
    var transactionTable = $('#transactionTable').DataTable({
            dom: 'rtip',
            processing: true,
            serverSide: true,
            responsive: true,
            "ordering": false,
            "ajax": {
                "url": "{!! 'transaction/datatable' !!}",
                "data": function (d) {
                    d.from_date = $("#from_date").val();
                    d.to_date = $("#to_date").val();
                }
            },
            columns: [
                { data: 'random_transaction_id', name: "random_transaction_id" },
                { data: 'date', name: 'created_at', class:'text-uppercase'},
                { data: 'provider_name', name: 'provider_name', class:'text-uppercase'},
                { data: 'amount', name: 'amount'},  
                { data: 'fullname', name: 'fullname', class:'text-uppercase'},   
                { data: 'debtorFullname', name: 'debtorFullname', class:'text-uppercase'},         
                {
                    data:  null,
                    name: 'status',
                    "render":function(o){
                        if(o.status == 'FAIL') {
                            return "<span class='red-txt'>FAIL</span>";
                        }
                        return "<span class='green-txt'>COMPLETE</span>";
                    }
                },
                {
                    data:  null,
                    "orderable": false,
                    "searchable": false,
                    "render":function(o){
                        return "<a href='{{ url('transaction') }}/" + o.id +"' >VIEW DETAILS</a>";
                    }
                },
            ],
            "drawCallback": function( settings ) {
                $($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
            },
            {{--  order: [["0", "DESC"]],  --}}
            {{--  fnInitComplete : function( oSettings, json ){
                $("#transactionTable_filter").detach().prependTo(".search_block");
            }  --}}
    });
{{--  
    $("div.dataTables_filter input").addClass('serach');
    $("div.dataTables_filter input").removeClass('input-sm');    
    $("div.dataTables_filter input").attr('placeholder', 'Search');  --}}

    $('.serach').on("input", function(){
        transactionTable.search($(this).val()).draw();
    });

    $("#datetimepicker2").datepicker({
        "format":"dd / M / yyyy",
    });
    $("#datetimepicker1").datepicker({
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