@extends('layouts.app')
@push('styles')
<link href="{{ asset('plugins/dataTables/dataTables.bootstrap.css') }}" rel="stylesheet">
<link href="{{ asset('plugins/dataTables/dataTables.responsive.css') }}" rel="stylesheet">
<link href="{{ asset('plugins/dataTables/dataTables.tableTools.min.css') }}" rel="stylesheet">
<link href="{{ asset('plugins/datepicker/datepicker3.css') }}" rel="stylesheet">

@endpush

@section('title', 'All Tickets')

@section('content')
<main>
    <section class="transactions">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="row calendar">
                          <label for="" class="col-md-3">Support</label>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class='input-group date' id='datetimepicker1'>
                                            <label>From</label> <input type='text' class="form-control from" id="from_date"/> <span class="input-group-addon">
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
                <div class="col-md-6">
                   <div class="new_ticket">
                    <a href="{{ url("tickets/create") }}" class="btn-primary">New ticket</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="records">
                        <div class="record_table">
                            <table class="table table-striped" id="ticketDatatable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Name</th>
                                        <th>Debtor</th>
                                        <th>Status</th>
                                        <th>Action</th>
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
{{--  <main>
<section class="main-123 container">
    <div class="row">
        <div class="col-md-12">
            <div class="records">
                <div class="record_table">
                    <div class="" style="text-align:right">
                        <a href="{{ url("tickets/create") }}" class="btn btn-primary block">Add Support Ticket</a>
                    </div>
                    <table id="ticketDatatable" class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Transaction Id</th>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Last Update</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Transaction Id</th>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Last Update</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
</main>  --}}
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
    var ticketTable = $('#ticketDatatable').DataTable({
            dom: 'rtip',
            processing: true,
            serverSide: true,
            responsive: true,
            ordering: false,
            "ajax": {
                "url": "{!! 'tickets/datatable' !!}",
                "data": function (d) {
                    d.from_date = $("#from_date").val();
                    d.to_date = $("#to_date").val();
                }
            },
            columns: [
                { data: 'id', "name": "id" },
                { data: 'date', name: 'created_at', class: 'text-uppercase'},
                { data: 'service_name', name: 'service_name', class: 'text-uppercase'},  
                { data: 'amount', name: 'amount'},     
                { data: 'fullname', name: 'fullname', class: 'text-uppercase'},
                { data: 'debtorFullname', name: 'debtorFullname', class: 'text-uppercase'},
                { data: 'status', name: 'status', class: 'text-uppercase'},                
                {
                    data:  null,
                    "orderable": false,
                    "searchable": false,
                    "render":function(o){
                        var str="";
                        str += "<a href='{{ url('tickets') }}/" + o.id +"' class='viewRecord text-uppercase green-txt' val='" + o.ticket_id + "'>View Details</a> ";
                        return str;
                    }
                }
            ],
            order: [["0", "DESC"]]
    });

    $("#datetimepicker2").datepicker({
        "format":"dd / M / yyyy",
    });
    $("#datetimepicker1").datepicker({
        "format":"dd / M / yyyy",
    });


    $("#from_date").on('change', function(){
        ticketTable.draw();
    });
    $("#to_date").on('change', function(){
        ticketTable.draw();
    });
});
</script>
@endpush