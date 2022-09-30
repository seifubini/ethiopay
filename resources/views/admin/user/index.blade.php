@extends('admin.layout.admin')
@section('title',"Users")

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
            <a class="btn btn-primary add-button" href="{{route('admin.users.create')}}" data-loading-text="Loading...">
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
                    <table id="userListTable" class="table table-striped table-bordered table-hover" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Total Spent</th>
                                <th>Total Transcations</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Total Spent</th>
                                <th>Total Transcations</th>
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
        var oTable = $('#userListTable').DataTable({
            "dom": '<"row" <"col-sm-4"l> <"col-sm-4"r> <"col-sm-4"f>> <"row"  <"col-sm-12"t>> <"row" <"col-sm-5"i> <"col-sm-7"p>>',
            stateSave: true,
            processing: true,
            serverSide: true,
            responsive: true,
            pagingType: "full_numbers",
            "ajax": {
                "url": "{!! url('admin/users/datatable') !!}",
                "data": function (d) {
                }
            },
            columns: [
                {data: 'id', name: 'id', "width": "5%", "searchable": false},
                {data: 'fullname', name: 'fullname', "width": "15%", "searchable": false},
                {data: 'email', name: 'email', "width": "15%", "searchable": false},
                {data: 'transactionsAmountSumDisplay', name: 'transactionsAmountSum', "width": "10%"},
                {data: 'transactionsCountDisplay', name: 'transactionsCount', "width": "15%", "searchable": false},
                {data: null,
                    "width": "10%",
                    "visible": false,
                    "orderable": false,
                    "searchable": false,
                    "render": function (o) {
                        var str = '';
                        if (o.deleted_at == "" || o.deleted_at == null) {
                            str = "<a href='users/" + o.id + "/edit' class='text-info'  title='Edit'><i class='fa fa-edit'></i></a> &nbsp;";
                            str += "<a href='users/" + o.id + "' class='text-info viewRecord'  data-id='" + o.id + "' title='View'><i class='fa fa-eye'></i></a> &nbsp;";
                            str += "<a href='javascript:void(0);' class='text-danger deleteRecord'  data-id='" + o.id + "' data-delete='0' title='Delete'><i class='fa fa-trash'></i></a>";
                        }
                        else {
                            str += "<a href='users/" + o.id + "' class='text-info resetRecord' data-id='" + o.id + "' title='Restore' ><i class='fa fa-mail-reply-all'></i></a> ";
                            str += "<a href='javascript:void(0);' class='text-danger deleteRecord'  data-id='" + o.id + "' data-delete='1' title='Parmanent Delete'><i class='fa fa-window-close'></i></a>";
                        }
                        return str;
                    }
                }
            ]
        });


        //delete Record
        jQuery(document).on('click', '.deleteRecord', function (event) {
            var id = $(this).data('id');
            var deleteType = $(this).data('delete');
            if (id == "")
                return false;
            var deleteUrl = "{!!  url('admin/users')  !!}/" + id;
            var isDelete = deleteRecordByAjax(deleteUrl, "@yield('title')", oTable, deleteType);
        });

//    jQuery(document).on('click', '.viewRecord', function (event) {
//        var id = $(this).data('id');
//        if (id == "")
//            return false;
//
//        var viewUrl = "{!!  url('admin/users')  !!}/" + id;
//        recordDetailByAjax(viewUrl, "@yield('title')");
//    });

        // Restore
        jQuery(document).on('click', '.resetRecord', function (event) {
            var id = $(this).data('id');
            if (id == "")
                return false;
            var restorUrl = "{!!  url('/admin/users/restore')  !!}/" + id;
            var isRestore = restorRecordByAjax(restorUrl, "@yield('title')", oTable);

        });
    });
</script>            
@endpush