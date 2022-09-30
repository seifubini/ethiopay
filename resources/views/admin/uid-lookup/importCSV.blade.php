@extends('admin.layout.admin')
@section('title',"Import CSV")

@section('pageHeading')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-4">
        <h2>@yield('title')</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{url('admin/dashboard')}}">Dashboard</a>
            </li>
            <li>
                <a href="{{url('admin/uid-lookup')}}">UID Lookup</a>
            </li>
            <li class="active">
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
                    <form id="importUidLookupForm" name="importUidLookupForm" class="form-horizontal" method="POST" action="{{ url('admin/uid-lookup/import-csv') }}" enctype="multipart/form-data" >
                        {{ csrf_field() }}
                        <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                            <label for="uidLookup" class="control-label col-lg-3">UID Lookup CSV File</label>
                            <div class="col-lg-6">
                                <input id="uidLookup" name="uidLookup" type="file" class="form-control">
                                @if ($errors->has('uidLookup'))
                                <label class="text-danger" for="service_type_id">{{ ($errors->first('uidLookup')) ? $errors->first('uidLookup') : '' }}</label>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-offset-3 col-lg-10">
                                <button id="submitBtn" name="submitBtn" type="submit" class="btn btn-primary submit-button" data-loading-text="Loading...">Submit</button>
                                <a href="{{ url('admin/uid-lookup/') }}" class="btn btn-danger">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    $("#importUidLookupForm").validate({
        errorElement: 'div',
        errorClass: 'text-danger text-left text-bold',
        rules: {
            uidLookup: {
                required: true,
                extension: "csv|CSV",
            },
        }, messages: {
            uidLookup: {
                extension: "Please upload valid csv file.",
            },
        },
        highlight: function (element) {
            $(element).closest('.form-control').addClass('has-error');
        },
        unhighlight: function (element) {
            $(element).closest('.form-control').removeClass('has-error');
        },
        errorPlacement: function (error, element) {
            error.insertAfter(element);
        },
        submitHandler: function (form) {
            $('#submitBtn').button('loading');
            form.submit();
        },
    });
</script>
@endpush
