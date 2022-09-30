@extends('admin.layout.admin')
@section('title',"SMS-Email Message")

@section('pageHeading')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-4">
        <h2>@yield('title')</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{url('admin/dashboard')}}">Dashboard</a>
            </li>
            <li>
                @yield('title')
            </li>
        </ol>
    </div>
    <div class="col-sm-8">
        <div class="title-action">
            <a class="btn btn-primary" href="{{url('admin/sms-email-message/create')}}">
                Add
            </a>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>@yield('title')</h5>
                </div>
                <div class="ibox-content">
                    <table id="smsEmailMessageTable" class="table table-striped table-bordered table-hover" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Message Type</th>
                                {{--  <th>Payours Name</th>
                                <th>Debtors Name</th>  --}}
                                <th>Sent Status</th>
                                <th>Sent Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Message Type</th>
                                {{--  <th>Payours Name</th>
                                <th>Debtors Name</th>  --}}
                                <th>Sent Status</th>
                                <th>Sent Date</th>
                                <th>Action</th>
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
<script type="text/javascript">
    $(document).ready(function () {
        var oTable = $('#smsEmailMessageTable').DataTable({
            "dom": '<"row" <"col-sm-4"l> <"col-sm-4"r> <"col-sm-4"f>> <"row"  <"col-sm-12"t>> <"row" <"col-sm-5"i> <"col-sm-7"p>>',
            stateSave: true,
            processing: true,
            serverSide: true,
            responsive: true,
            order: [0, "desc"],
            pagingType: "full_numbers",
            "ajax": {
                "url": "{!! url('admin/sms-email-message/datatable') !!}",
                "data": function (d) {
                }
            },
            columns: [
                {data: 'id', name: 'id', "width": "10%"},
                {data: 'title', name: 'title', "width": "40%"},
                {data: 'messageType', name: 'message_type', "width": "15%"},
                {{--  {data: 'payoursName', name: 'payoursName'},
                {data: 'debtorsName', name: 'debtorsName'},  --}}
                {
                    data:  null,
                    name: 'sentStatus',
                    "render":function(o){
                        if(o.sentStatus == 'Sent') {
                            return "<button class='btn btn-primary'>" + o.sentStatus + "</button>";
                        }
                        else {
                            return "<button class='btn btn-warning'>" + o.sentStatus + "</button>";
                        }
                    },
                    "width": "15%"
                },
                {data: 'sentDate', name: 'sentDate', "width": "25%"},
                {
                    data: null,
                    "width": "10%",
                    "visible": false,
                    "orderable": false,
                    "searchable": false,
                    "render": function (o) {
                        var str = '';
                        str += "<a href='sms-email-message/" + o.id + "' class='text-info viewRecord'  data-id='" + o.id + "' title='View'><i class='fa fa-eye'></i></a> &nbsp;";
                        if (o.sent_type == 'schedule' && o.sent_status == '0') {
                            str += "<a href='sms-email-message/" + o.id + "/edit' class='text-info'  title='Edit'><i class='fa fa-edit'></i></a> &nbsp;";
                            str += "<a href='javascript:void(0);' class='text-danger deleteRecord'  data-id='" + o.id + "' data-delete='0' title='Delete'><i class='fa fa-trash'></i></a>";
                        }
                        return str;
                    }
                }
            ]
        });

        jQuery(document).on('click', '.deleteRecord', function (event) {
            var id = $(this).data('id');
            var deleteType = $(this).data('delete');
            if (id == "")
                return false;
            var deleteUrl = "{!!  url('admin/sms-email-message')  !!}/" + id;
            var isDelete = deleteRecordByAjax(deleteUrl, "@yield('title')", oTable, deleteType);
        });
    });
</script>            
@endpush