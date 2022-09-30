@extends('admin.layout.admin')

@push('styles')
<link href="{{ asset('plugins/dataTables/dataTables.bootstrap.css') }}" rel="stylesheet">
<link href="{{ asset('plugins/dataTables/dataTables.responsive.css') }}" rel="stylesheet">
<link href="{{ asset('plugins/dataTables/dataTables.tableTools.min.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/datepicker/datepicker3.css') }}">


<style>
    .labelblk{
        margin-bottom:15px;
    }
    .labelblk label {
        float: left;
        margin: 6px 20px 0 0;
        width: 40px;
    }
    .labelblk input {
        float: left;
        width: calc(100% - 60px);
    }
    #tabmenu > li > a {
        color: black;
    }
</style>

@endpush

@section("title", "Payment Methods")

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
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5> @yield('title')</h5>
                    {{--  <a href="{{ url("admin/users/$user->id/payment-methods/create") }}">Add Payment Method</a>  --}}
                    <div class="ibox-tools">
                        <a class="btn btn-primary add-button" href="{{ url("admin/users/$user->id/payment-methods/create") }}" data-loading-text="Loading...">
                            Add
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <table id="paymentMethodsTable" class="table table-striped table-bordered table-hover" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Method Type</th>
                                <th>Paypal Email</th>
                                <th>Card Number</th>
                                <th>Spent Amount</th>
                                <th>Option</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Method Type</th>
                                <th>Paypal Email</th>
                                <th>Card Number</th>
                                <th>Spent Amount</th>
                                <th>Option</th>
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
<script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>

<script type="text/javascript">

    $(document).ready(function() {
        var paymentMethodsTable = $('#paymentMethodsTable').DataTable({
                {{--  dom: 'rtip',  --}}
                processing: true,
                serverSide: true,
                responsive: true,
                "ajax": {
                    "url": "{{ route('admin.users.payment-methods.datatable', ['id' => $user->id]) }}",
                    "data": function (d) {
                    }
                },
                columns: [
                    { data: 'id', name: "id" },
                    { data: 'method_type', name: 'method_type'},
                    { data: 'paypal_email', name: 'paypal_email'},
                    { data: 'cardTypeNumber', name: 'cardTypeNumber'},  
                    { data: 'transactionsAmountSumDisplay', name: 'transactionsAmountSum', "searchable": false},     
                    {
                        data:  null,
                        "orderable": false,
                        "searchable": false,
                        "render":function(o){
                            {{--  return "<a href='{{ url('admin/users') }}/" + {{ $user->id }} + "/payment-methods/create' class='text-info viewRecord'><i class='fa fa-eye'></i></a>";  --}}
                        }
                    },
                ],
                fnInitComplete : function( oSettings, json ){
                    $(".from_date").detach().appendTo("#transactionTable_filter");
                }
        });
    });
</script>
@endpush