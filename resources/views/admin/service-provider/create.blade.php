
@extends('admin.layout.admin')

@push('styles')
@endpush

@section('title',"Add Service Provider")

@section('content')

@section('pageHeading')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-8">
        <h2>@yield('title')</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{url('admin/dashboard')}}">Dashboard</a>
            </li>
            <li>
                <a href="{{url('admin/service-provider')}}">{{ isset($module_name) ? $module_name : '' }}</a>
            </li>
            <li class="active">
                @yield('title')
            </li>
        </ol>
    </div>
    <div class="col-sm-4">
        <div class="title-action">
            <a class="btn btn-primary" href="{{route('admin.service-providers.create')}}">
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
                    {!! Form::open(['url' => 'admin/service-providers', 'method' => 'POST',"enctype"=>"multipart/form-data",'class'=>'form-horizontal','id'=>'service-provider']) !!}
                    @include("admin.service-provider._form")
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

        $("#service-provider").validate({
            errorElement:'div',
            errorClass: 'text-danger text-left text-bold',
            rules: {
                service_type_id: {
                    required: true,
                },
                service_id: {
                    required: true,
                },
                provider_name: {
                    required: true,
                }
            },
            messages: {
                service_type_id: {
                    required: "Service type is required",
                },
                service_id: {
                    required: "Service name is required",
                },
                provider_name: {
                    required: "Service provider is required",
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

