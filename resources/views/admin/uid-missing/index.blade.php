@extends('admin.layout.admin')
@section('title',"UID Missing")

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

@section('content')
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>@yield('title')</h5>
                </div>
                <div class="ibox-content">
                    <table id="uidMissingListTable" class="table table-striped table-bordered table-hover" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Service Type</th>
                                <th>User</th>
                                <th>UID</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Service Type</th>
                                <th>User</th>
                                <th>UID</th>
                                <th>Created At</th>
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
        var oTable = $('#uidMissingListTable').DataTable({
            "dom": '<"row" <"col-sm-4"l> <"col-sm-4"r> <"col-sm-4"f>> <"row"  <"col-sm-12"t>> <"row" <"col-sm-5"i> <"col-sm-7"p>>',
            stateSave: true,
            processing: true,
            serverSide: true,
            responsive: true,
            order: [0, "desc"],
            pagingType: "full_numbers",
            "ajax": {
                "url": "{!! url('admin/uid-missing/datatable') !!}",
                "data": function (d) {
                }
            },
            columns: [
                {data: 'id', name: 'id', "width": "20%"},
                {data: 'service_type_name', name: 'service_type_name', "width": "20%"},
                {data: 'user_fullname', name: 'user_fullname', "width": "20%"},
                {data: 'uid', name: 'uid', "width": "20%"},                
                {data: 'created_at_timezone', name: 'created_at', "width": "20%"}
            ]
        });
    });
</script>            
@endpush