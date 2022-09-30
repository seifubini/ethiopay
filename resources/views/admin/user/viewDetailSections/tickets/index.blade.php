@extends('admin.layout.admin')
@push('styles')
<link href="{{ asset('plugins/dataTables/dataTables.bootstrap.css') }}" rel="stylesheet">
<link href="{{ asset('plugins/dataTables/dataTables.responsive.css') }}" rel="stylesheet">
<link href="{{ asset('plugins/dataTables/dataTables.tableTools.min.css') }}" rel="stylesheet">
@endpush

@section('title',"Tickets")

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
    <div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Ticket List</h5>
            <div class="ibox-tools">
                <a class="collapse-link">
                    <i class="fa fa-chevron-up"></i>
                </a>
            </div>
        </div>
        <div class="ibox-content referral_table">
        <div class="">
            <table id="ticketDatatable" class="table table-striped table-bordered table-hover" width="100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Transaction Id</th>
                        <th>Title</th>
                        <th>User</th>
                        <th>Debtor</th>
                        <th>Status</th>
                        <th>Admin Name</th>
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
                        <th>User</th>
                        <th>Debtor</th>
                        <th>Status</th>
                        <th>Admin Name</th>
                        <th>Last Update</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')

{{-- Data table --}}
<script src="{{ asset('plugins/dataTables/jquery.dataTables.js') }}"></script>
<script src="{{ asset('plugins/dataTables/dataTables.bootstrap.js') }}"></script>
<script src="{{ asset('plugins/dataTables/dataTables.responsive.js') }}"></script>
<script src="{{ asset('plugins/dataTables/dataTables.tableTools.min.js') }}"></script>

<script type="text/javascript">

$(document).ready(function() {
    var ticketTable = $('#ticketDatatable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            pagingType: "full_numbers",
            "ajax": {
                "url": "{{ route('admin.users.tickets.datatable', ['id' => $user->id]) }}",
                "data": function (d) {
                }
            },
            columns: [
                { data: 'id', "name": "id" },
                { data: 'transaction_id', name: 'transaction_id'},
                { data: 'title', "name": 'title'},
                { data: 'userFullname', name: 'userFullname'},   
                { data: 'debtorFullname', name: 'debtorFullname'}, 
                {
                    data:  null,
                    name: 'status',
                    "render":function(o){
                        var str="";
                        if(o.status == 'Open') {
                            str += "<select class='change-status' data-id='" + o.id + "'><option value='Open' selected>Open</option><option value='Closed'>Closed</option><option value='Urgent'>Urgent</option></select>";
                        }
                        if(o.status == 'Closed') {
                            str += "<select class='change-status' data-id='" + o.id + "'><option value='Open'>Open</option><option value='Closed' selected>Closed</option><option value='Urgent'>Urgent</option></select>";
                        }
                        if(o.status == 'Urgent') {
                            str += "<select class='change-status' data-id='" + o.id + "'><option value='Open'>Open</option><option value='Closed'>Closed</option><option value='Urgent' selected>Urgent</option></select>";
                        }
                        return str;
                    }
                },
                {   
                    data: 'lastResponseAdminName', 
                    name: 'lastResponseAdminName', 
                    "orderable": false,
                    "searchable": false
                },
                {   
                    data: 'lastUpdateDate', 
                    name: 'lastUpdateDate', 
                    "orderable": false,
                    "searchable": false
                },                
                {
                    data:  null,
                    "orderable": false,
                    "searchable": false,
                    "render":function(o){
                        var str="";
                        str += "<a href='{{ url('admin/users') }}/" + {{ $user->id }} + "/tickets/" + o.ticket_id + "' class='viewRecord' val='" + o.ticket_id + "'><i class='glyphicon glyphicon-eye-open'></i></a> ";
                        
                        return str;
                    }
                }
            ],
            order: [["0", "DESC"]]
    });

    jQuery(document).on('click', '.change-status', function(event) {
        var id = $(this).data('id');
        var status = $(this).val();
        var newstatus = $(this).data('newstatus');
        var alert_message = "Are you want to change status?";
        var alert_success_message = "Status change successfully!";

        if(id != "") {
            swal({
                title: "Are you sure?",
                text: alert_message,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, Change it!",
                cancelButtonText: "No, cancel!",
                showLoaderOnConfirm: true,
                allowOutsideClick:false,
                allowEscapeKey:false,
                preConfirm: function (email) {
                    return new Promise(function (resolve, reject) {
                        setTimeout(function() {
                            jQuery.ajax({
                                url: "{{ url('admin/users') }}/" + {{ $user->id }} + "/change-ticket-status/" + id ,
                                type: 'GET',
                                dataType: 'json',
                                data: {
                                    "status": status
                                },
                                success: function (result) {
                                    ticketTable.draw();
                                    swal("success!", alert_success_message, "success");
                                    fnToastSuccess(alert_success_message);
                                },
                                error: function (xhr, status, error) {
                                    ticketTable.draw();
                                    console.log(xhr);
                                    if(xhr.responseJSON && xhr.responseJSON.message!=""){
                                        swal("ohh snap!", xhr.responseJSON.message, "error");
                                    } else {
                                        swal("ohh snap!", "Something went wrong", "error");
                                    }
                                    ajaxError(xhr, status, error);
                                }
                            });
                        }, 0)
                    })
                },
            }).then(function() {
                }, function(dismiss) {
                if (dismiss === 'cancel') { 
                    ticketTable.draw();
                } 
            })
        }
    });
});
</script>
@endpush