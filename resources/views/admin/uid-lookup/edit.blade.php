@extends('admin.layout.admin')
@section('title',"EditUID Lookup")

@section('pageHeading')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-12">
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
                    <form id="uidLookupEditForm" name="uidLookupEditForm" class="form-horizontal" method="post" action="{{ url('admin/uid-lookup/'.$uIdLookup->id)}}">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <div class="form-group">
                            <label for="service_type_id" class="control-label col-lg-3">Service Type</label>
                            <div class="col-lg-6">
                                <select id="service_type_id" name="service_type_id" class="form-control">
                                    <option value="">Select Service Type</option>
                                    @foreach($serviceType as $s)
                                        <option value="{{ $s->id }}" {{ (old('service_type_id') ? old('service_type_id') : $uIdLookup->service_type_id ) == $s->id ? 'selected' : '' }} >{{ $s->service_name }}</option>
                                    @endforeach
                                </select>
                                @if($errors)
                                    <label class="text-danger" for="service_type_id">{{ ($errors->first('service_type_id')) ? $errors->first('service_type_id') : '' }}</label>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="uid" class="control-label col-lg-3">UID</label>
                            <div class="col-lg-6">
                                <input id="uid" name="uid" type="text" class="form-control" value="{{ old('uid') ? old('uid') : $uIdLookup->uid }}">
                                @if($errors)
                                    <label class="text-danger" for="uid">{{ ($errors->first('uid')) ? $errors->first('uid') : '' }}</label>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="debtor_firstname" class="control-label col-lg-3">Debtor Firstname</label>
                            <div class="col-lg-6">
                                <input id="debtor_firstname" name="debtor_firstname" type="text" class="form-control" value="{{ old('debtor_firstname') ? old('debtor_firstname') : $uIdLookup->debtor_firstname}}">
                                @if($errors)
                                    <label class="text-danger" for="debtor_firstname">{{ ($errors->first('debtor_firstname')) ? $errors->first('debtor_firstname') : '' }}</label>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="debtor_lastname" class="control-label col-lg-3">Debtor Lastname</label>
                            <div class="col-lg-6">
                                <input id="debtor_lastname" name="debtor_lastname" type="text" class="form-control" value="{{ old('debtor_firstname') ? old('debtor_lastname') : $uIdLookup->debtor_lastname}}">
                                @if($errors)
                                    <label class="text-danger" for="debtor_lastname">{{ ($errors->first('debtor_lastname')) ? $errors->first('debtor_lastname') : '' }}</label>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="debtor_city" class="control-label col-lg-3">Debtor City</label>
                            <div class="col-lg-6">
                                <input id="debtor_city" name="debtor_city" type="text" class="form-control" value="{{ old('debtor_city') ? old('debtor_city') : $uIdLookup->debtor_city}}">
                                @if($errors)
                                    <label class="text-danger" for="debtor_city">{{ ($errors->first('debtor_city')) ? $errors->first('debtor_city') : '' }}</label>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="amount" class="control-label col-lg-3">Amount</label>
                            <div class="col-lg-6">
                                <input id="amount" name="amount" type="text" class="form-control" value="{{ old('amount') ? old('amount') : $uIdLookup->amount }}">
                                @if($errors)
                                    <label class="text-danger" for="amount">{{ ($errors->first('amount')) ? $errors->first('amount') : '' }}</label>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="cut_off_date" class="control-label col-lg-3">Cut Off Date</label>
                            <div class="col-lg-6">
                                <div class='input-group col-lg-12' id='datepicker'>
                                    <input type='text' name="cut_off_date" id="cut_off_date" class="form-control" id="date" value="{{ old('cut_off_date') ? old('cut_off_date') : $uIdLookup->cut_off_date }}"/>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                                {{--  <input id="cut_off_date" name="cut_off_date" type="text" class="form-control" value="{{ old('cut_off_date') }}">  --}}
                                @if($errors)
                                    <label class="text-danger" for="cut_off_date">{{ ($errors->first('cut_off_date')) ? $errors->first('cut_off_date') : '' }}</label>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-lg-offset-3 col-lg-10">
                                <button id="submitBtn" name="submitBtn" type="submit" class="btn btn-primary submit-button" data-loading-text="Loading...">Submit</button>
                                <a href="{{ url('admin/uid-lookup') }}" class="btn btn-danger">Cancel</a>
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

    $('#amount').keypress(function(event) {
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) &&
          ((event.which < 48 || event.which > 57) &&
            (event.which != 0 && event.which != 8))) {
          event.preventDefault();
        }
      
        var text = $(this).val();
      
        if ((text.indexOf('.') != -1) &&
          (text.substring(text.indexOf('.')).length > 2) &&
          (event.which != 0 && event.which != 8) &&
          ($(this)[0].selectionStart >= text.length - 2)) {
          event.preventDefault();
        }
    });
    
    var addCurrentUtcDateObj = moment(moment().tz(TIMEZONE_STR).format('YYYY-MM-DD'));

    $('#uidLookupEditForm #datepicker').datetimepicker({
        "locale": "en",
        "format":"YYYY-MM-DD",
        defaultDate: addCurrentUtcDateObj.format('YYYY-MM-DD'),
    });

    $("#uidLookupEditForm").validate({
        ignore: [],
        errorElement: 'div',
        errorClass: 'text-danger text-left text-bold',
        rules: {
            service_type_id: {
                required: true,
            },
            uid: {
                required: true,
            },
            debtor_firstname: {
                required: true,
            },
            debtor_lastname: {
                required: true,
            },
            debtor_city: {
                required: true,
            },
            amount: {
                required: true,
                number: true,
                min: 0,
            },
            cut_off_date: {
                required: true,
            },
        }, messages: {
        },
        highlight: function (element) {
            $(element).closest('.form-control').addClass('has-error');
        },
        unhighlight: function (element) {
            $(element).closest('.form-control').removeClass('has-error');
        },
        errorPlacement: function (error, element) {
            if (element.attr('name')=='cut_off_date') {
                error.insertAfter($('#datepicker'));
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function (form) {
            $('.submit-button').button('loading');
            form.submit();
        },
    });
</script>
@endpush
