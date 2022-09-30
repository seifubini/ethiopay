@extends('admin.layout.admin')



@push('styles')
{{-- Data table --}}
<link href="{{ asset('plugins/dataTables/dataTables.bootstrap.css') }}" rel="stylesheet">
<link href="{{ asset('plugins/dataTables/dataTables.responsive.css') }}" rel="stylesheet">
<link href="{{ asset('plugins/dataTables/dataTables.tableTools.min.css') }}" rel="stylesheet">

{{-- sweetalert2 --}}
<link href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">
@endpush

@section('content')

@section('title',"Settings")

@section('pageHeading')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-12">
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
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5> @yield('title')</h5>
                </div>
                <div class="ibox-content">
                    <table id="datatable" class="table table-striped table-bordered table-hover" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Key</th>
                                <th>Value</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Key</th>
                                <th>Value</th>
                                <th>Date</th>
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

{{-- Data table --}}
<script src="{{ asset('plugins/dataTables/jquery.dataTables.js') }}"></script>
<script src="{{ asset('plugins/dataTables/dataTables.bootstrap.js') }}"></script>
<script src="{{ asset('plugins/dataTables/dataTables.responsive.js') }}"></script>
<script src="{{ asset('plugins/dataTables/dataTables.tableTools.min.js') }}"></script>

{{-- Sweetalert2 --}}
<script src="{{ asset('plugins/sweetalert2/es6-promise.auto.min.js') }} "></script> 
<!-- for IE support -->
<script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }} "></script> 

<script type="text/javascript">

$(document).ready(function () {

    var oTable = $('#datatable').DataTable({
        "dom": '<"row" <"col-sm-4"l> <"col-sm-4"r> <"col-sm-4"f>> <"row"  <"col-sm-12"t>> <"row" <"col-sm-5"i> <"col-sm-7"p>>',
        stateSave: true,
        processing: true,
        serverSide: true,
        responsive: true,
        pagingType: "full_numbers",
        "ajax": {
            "url": "{!! url('admin/settings/datatable') !!}",
            "data": function (d) {
            }
        },
        columns: [
            {data: 'id', name: 'id', "width": "10%"},
            {data: 'key', name: 'key', "width": "25%"},
            {data: 'value', name: 'value', "width": "25%"},
            {data: 'date', name: 'created_at', "width": "25%"},
            {data: null,
                "width": "15%",
                "orderable": false,
                "searchable": false,
                "render": function (o) {
                    var str = '';
                    str = "<a href='settings/" + o.id + "/edit' class='text-info'  title='Edit'><i class='fa fa-edit'></i></a> &nbsp;";
                    str += "<a href='javascript:void(0);' class='text-info viewRecord'  data-id='" + o.id + "' title='View'><i class='fa fa-eye'></i></a> &nbsp;";
                    return str;
                }
            }
        ]
    });


    //delete Record
    jQuery(document).on('click', '.deleteRecord', function (event) {
        var id = $(this).data('id');
        if (id == "")
            return false;
        var deleteUrl = "{!!  url('admin/settings')  !!}/" + id;
        var isDelete = deleteRecordByAjax(deleteUrl, "@yield('title')", oTable);
    });

    //view Record
    jQuery(document).on('click', '.viewRecord', function (event) {
        var id = $(this).data('id');
        if (id == "")
            return false;

        var viewUrl = "{!!  url('admin/settings')  !!}/" + id;
        recordDetailByAjax(viewUrl, "@yield('title')");
    });


});

</script>            
@endpush