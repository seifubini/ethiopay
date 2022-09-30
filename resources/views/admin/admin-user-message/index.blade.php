@extends('admin.layout.admin')
@section('title',"Admin-User Message")

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
    <!--    <div class="col-sm-8">
            <div class="title-action">
                <a class="btn btn-primary" href="{{url('admin/admin-user-message/create')}}">
                    Add
                </a>
            </div>
        </div>-->
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
                    <table id="adminUserMessageListTable" class="table table-striped table-bordered table-hover" width="100%">
                        <thead>
                            <tr>
                                <td>User</td>
                                <td>Email</td>
                                <td>Total Message Count</td>
                                <td>Last Updated</td>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <td>User</td>
                                <td>Email</td>
                                <td>Total Message Count</td>
                                <td>Last Updated</td>
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
        var oTable = $('#adminUserMessageListTable').DataTable({
            "dom": '<"row" <"col-sm-4"l> <"col-sm-4"r> <"col-sm-4"f>> <"row"  <"col-sm-12"t>> <"row" <"col-sm-5"i> <"col-sm-7"p>>',
            stateSave: true,
            processing: true,
            serverSide: true,
            responsive: true,
            order: [3, "desc"],
            pagingType: "full_numbers",
            "ajax": {
                "url": "{!! url('admin/admin-user-message/datatable') !!}",
                "data": function (d) {
                }
            },
            columns: [
                {data: 'user_fullname', name: 'user_fullname', "width": "20%"},
                {data: 'email', name: 'email', "width": "20%"},
                {
                    data: 'msgTotalCount',
                    name: 'msgTotalCount',
                    width: "20%",
                    orderable: false,
                    searchable: false,
                },
                {data: 'updated_at_timezone', name: 'updated_at', "width": "20%"},
                {data: null,
                    "width": "20%",
                    "visible": false,
                    "orderable": false,
                    "searchable": false,
                    "render": function (o) {
                        var str = '';
                            str += "<a href='{{ url('admin/admin-user-message') }}/" + o.userId + "' class='text-info viewRecord' title='View'><i class='fa fa-eye'></i></a> &nbsp;";
                        return str;
                    }
                }
            ]
        });
    });
</script>            
@endpush