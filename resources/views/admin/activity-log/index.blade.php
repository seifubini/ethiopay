@extends('admin.layout.admin')
@push('styles')
<link href="{{ asset('plugins/dataTables/dataTables.bootstrap.css') }}" rel="stylesheet">
<link href="{{ asset('plugins/dataTables/dataTables.responsive.css') }}" rel="stylesheet">
<link href="{{ asset('plugins/dataTables/dataTables.tableTools.min.css') }}" rel="stylesheet">
@endpush


@section('content')
@section('title',"Activity Log")

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
    </div>

@endsection

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
            <table id="activityLogTable" class="table table-striped table-bordered table-hover" width="100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Type</th>
                        <th>Subject</th>
                        <th>Ip</th>
                        <th>Admin Name</th>
                        <th>Method</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Type</th>
                        <th>Subject</th>
                        <th>Ip</th>
                        <th>Admin Name</th>
                        <th>Method</th>
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
    var activityLogTable = $('#activityLogTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            pagingType: "full_numbers",
            "ajax": {
                "url": "{{ url('admin/activity-log/datatable') }}",
                "data": function (d) {
                }
            },
            columns: [
                { data: 'id', "name": "id" },
                { data: 'type', name: 'type'},
                { data: 'subject', "name": 'subject'},
                { data: 'ip', name: 'ip'},
                { data: 'name', name: 'name'},   
                { data: 'method', name: 'method'}, 
                {
                    data:  null,
                    "orderable": false,
                    "searchable": false,
                    "render":function(o){
                        var str="";
                        str += "<a href='{{ url('admin/activity-log') }}/" + o.id +"' class='viewRecord' val='" + o.id + "'><i class='glyphicon glyphicon-eye-open'></i></a> ";
                        return str;
                    }
                }
            ],
            order: [["0", "DESC"]]
    });
});
</script>
@endpush