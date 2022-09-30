
@extends('admin.layout.admin')

@push('styles')
@endpush

@section('title',"Add Service Type")

@section('content')

@section('pageHeading')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-4">
        <h2>@yield('title')</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{url('admin/dashboard')}}">Dashboard</a>
            </li>
            <li>
                <a href="{{url('admin/service-types')}}">{{ isset($module_name) ? $module_name : '' }}</a>
            </li>
            <li class="active">
                @yield('title')
            </li>
        </ol>
    </div>
    <div class="col-sm-8">
        <div class="title-action">
            <a class="btn btn-primary" href="{{route('admin.service-types.create')}}">
                Add
            </a>
        </div>
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
                    {!! Form::open(['url' => 'admin/service-types', 'method' => 'POST',"enctype"=>"multipart/form-data",'class'=>'form-horizontal','id'=>'service-type']) !!}
                    @include("admin.service-type._form")
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@push('scripts')

<script>
    $(document).ready(function () {

        $("#service-type").validate({
            errorElement: 'div',
            errorClass: 'text-danger text-left text-bold',
            rules: {
                service_name: {
                    required: true,
                },
                payment_fee_in_percentage: {
                    required: true,
                    number: true,
                    min: 0,
                    max: 100,
                }
            },
            messages: {
                service_name: {
                    required: "Service name is required",
                },
                payment_fee_in_percentage: {
                    required: "Payment fee is required",
                }
            },
            errorPlacement: function (error, element) {
                if (element.attr('name') == 'payment_fee_in_percentage') {
                    error.insertAfter($('#payment_fee'));
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function (form) {
                $('.submit-button').button('loading');
                form.submit();
            },
            highlight: function (element) {
                $(element).closest('.form-control').addClass('has-error');
            },
            unhighlight: function (element) {
                $(element).closest('.form-control').removeClass('has-error');
            },
        });
    });
</script>
@endpush

