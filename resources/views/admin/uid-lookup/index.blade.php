@extends('admin.layout.admin')
@section('title',"UID Lookup")

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
            <a class="btn btn-primary" id='export-csv'>
                Export CSV
            </a>
            <a class="btn btn-primary" href="{{route('admin.uid-lookup.import-csv')}}">
                Import CSV
            </a>
            <a class="btn btn-primary add-button" href="{{route('admin.uid-lookup.create')}}" data-loading-text="Loading...">
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
                    <table id="uidLookupListTable" class="table table-striped table-bordered table-hover" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Service Type</th>
                                <th>UID</th>
                                <th>Debtor Name</th>
                                <th>Debtor City</th>
                                <!--<th>Debtor Phone</th>-->
                                <th>Amount</th>
                                <th>Cut Off Date</th>
<!--                                <th>Billing Period Start</th>
                                <th>Billing Period End</th>-->
                                <th>Updated At</th>
                                <th>Action</th>
                                <th class="hidden">service_type_id</th>
                                <th class="hidden">uid</th>
                                <th class="hidden">debtor_firstname</th>
                                <th class="hidden">debtor_lastname</th>
                                <th class="hidden">debtor_city</th>
                                <th class="hidden">amount</th>
                                <th class="hidden">cut_off_date</th>
                                
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Service Type</th>
                                <th>UID</th>
                                <th>Debtor Name</th>
                                <th>Debtor City</th>
                                <!--<th>Debtor Phone</th>-->
                                <th>Amount</th>
                                <th>Cut Off Date</th>
<!--                                <th>Billing Period Start</th>
                                <th>Billing Period End</th>-->
                                <th>Updated At</th>
                                <th>Action</th>
                                <th class="hidden">service_type_id</th>
                                <th class="hidden">uid</th>
                                <th class="hidden">debtor_firstname</th>
                                <th class="hidden">debtor_lastname</th>
                                <th class="hidden">debtor_city</th>
                                <th class="hidden">amount</th>
                                <th class="hidden">cut_off_date</th>
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
<script src="{{ asset('plugins/dataTables/buttons/buttons.html5.min.js') }}"></script>
<script src="{{ asset('plugins/dataTables/buttons/dataTables.buttons.min.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function () {
        var oTable = $('#uidLookupListTable').DataTable({
            "dom": '<"row" <"col-sm-4"l> <"col-sm-4"r> <"col-sm-4"f>> <"row"  <"col-sm-12"t>> <"row" <"col-sm-5"i> <"col-sm-7"p>>',
            stateSave: true,
            processing: true,
            serverSide: true,
            responsive: true,
            order: [0, "desc"],
            pagingType: "full_numbers",
            "ajax": {
                "url": "{!! url('admin/uid-lookup/datatable') !!}",
                "data": function (d) {
                }
            },
            buttons: [{
                extend: 'csv',
                name : 'csv',
                exportOptions: {
                    columns: [ 9, 10, 11, 12, 13, 14, 15 ],
                },
            }],
            columns: [
                {data: 'id', name: 'id', "width": "10%"},
                {data: 'service_type_name', name: 'service_type_name', "width": "15%"},
                {data: 'uid', name: 'uid', "width": "10%"},
                {data: 'debtor_fullname', name: 'debtor_fullname', "width": "20%"},
                {data: 'debtor_city', name: 'debtor_city', "width": "10%"},
//                {data: 'debtor_phone', name: 'debtor_phone', "width": "10%"},
                {data: 'amountDisplay', name: 'amount', "width": "10%"},
                {data: 'cut_off_date', name: 'cut_off_date', "width": "10%"},
//                {data: 'billing_period_start', name: 'billing_period_start', "width": "10%"},
//                {data: 'billing_period_end', name: 'billing_period_end', "width": "10%"},
                {data: 'updateDate', name: 'updated_at', "width": "10%"},
                {data: null,
                    "width": "5%",
                    "orderable": false,
                    "searchable": false,
                    "render": function (o) {
                        var str = '';
                        str = "<a href='uid-lookup/" + o.id + "/edit' class='text-info'  title='Edit'><i class='fa fa-edit'></i></a> &nbsp;";
                        return str;
                    }
                },
                {data: 'service_type_id', name: 'service_type_id', searchable: false, class: "hidden"},
                {data: 'uid', name: 'uid', searchable: false, class: "hidden"},
                {data: 'debtor_firstname', name: 'debtor_firstname', searchable: false, class: "hidden"},
                {data: 'debtor_lastname', name: 'debtor_lastname', searchable: false, class: "hidden"},
                {data: 'debtor_city', name: 'debtor_city', searchable: false, class: "hidden"},
                {data: 'amount', name: 'amount', searchable: false, class: "hidden"},
                {data: 'cut_off_date', name: 'cut_off_date', searchable: false, class: "hidden"},
                
            ]
        });

        $('#export-csv').click(function(){
            oTable.button('0').trigger();
        });
    });
</script>            
@endpush