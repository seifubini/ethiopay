@extends('admin.layout.admin')
@section('title',"SMS-Email Message Add")

@section('pageHeading')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-12">
        <h2>@yield('title')</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{url('admin/dashboard')}}">Dashboard</a>
            </li>
            <li>
                <a href="{{url('admin/sms-email-message')}}">SMS-Email Message</a>
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
                    <form id="smsEmailMessageAddForm" name="smsEmailMessageAddForm" class="form-horizontal" method="POST" action="javascript:void(0)">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="message_type" class="control-label col-lg-3">Message Type</label>
                            <div class="col-lg-6">
                                <select id="message_type" name="message_type" class="form-control">
                                    <option value="">Select message type</option>
                                    <option value="email">Email</option>
                                    <option value="sms">SMS</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="users_selection_type" class="control-label col-lg-3">User Selection</label>
                            <div class="col-lg-6">
                                <select id="users_selection_type" name="users_selection_type" class="form-control">
                                    <option value="">Select User Selection</option>
                                    <option value="all">All</option>
                                    <option value="selected">Selected User Only</option>
                                </select>
                            </div>
                        </div>
                        
                        <div id="usersIdSection" class="form-group hide">
                            <label for="users_id[]" class="control-label col-lg-3">Payours</label>
                            <div class="col-lg-6">
                                <select id="users_id" name="users_id[]" class="form-control select-multiple ignore" multiple="multiple">
                                    <option value="" disabled>Select Users</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->fullname . ' (' . $user->email . ')' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div id="debtorPhoneSection" class="form-group hide">
                            <label for="debtors_phone[]" class="control-label col-lg-3">Debtors</label>
                            <div class="col-lg-6">
                                <select id="debtors_phone" name="debtors_phone[]" class="form-control select-multiple" multiple="multiple">
                                    <option value="" disabled>Select Debtor</option>
                                    @foreach($debtorsPhone as $debtorPhone)
                                        <option value="{{ $debtorPhone->debtorPhoneNumber }}">{{ $debtorPhone->debtor_firstname . ' ' . $debtorPhone->debtor_lastname . ' (' . $debtorPhone->debtorPhoneNumber . ')' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="title" class="control-label col-lg-3">Title</label>
                            <div class="col-lg-6">
                                <input id="title" name="title" type="text" class="form-control">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="description" class="control-label col-lg-3">Description</label>
                            <div class="col-lg-6">
                                <textarea id="description" name="description" class="form-control textarea-large"></textarea>
                            </div>
                        </div>

                        <div class="form-group clearfix">
                            <label class="control-label col-lg-3">Send :</label>
                            <div class="col-lg-6">
                                <div class="radiobtnblk">
                                    <div class="radiobtn">
                                        <input type="radio" name="sent_type" class="sent_type" id="sent_type_now" value="now" checked>
                                        <label for="sent_type_now" class="radio-inline">
                                            Now
                                        </label>
                                    </div>
                                    <div class="radiobtn">
                                        <input type="radio" name="sent_type" class="sent_type" id="sent_type_schedule" value="schedule">
                                            <label for="sent_type_schedule" class="radio-inline">
                                            Later
                                        </label>
                                    </div>
                                </div>
                                <input type='hidden' name="sentdatetime" class="form-control" id="sentdatetime"/>                                
                                <div class="dateblk clearfix hidden">
                                    <label>Date :</label>
                                    <div class='input-group date' id='datepicker'>
                                        <input type='text' name="sentdate" class="form-control" id="date"/>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="timeblk clearfix hidden">
                                    <label>Time :</label>
                                    <input type="text" id="time" data-format="HH:mm" data-template="hh mm A" name="senttime">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-lg-offset-3 col-lg-10">
                                <button id="submitBtn" name="submitBtn" type="submit" class="btn btn-primary submit-button" data-loading-text="Loading...">Submit</button>
                                <a href="{{ url('admin/sms-email-message') }}" class="btn btn-danger">Cancel</a>
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
    var users_selection_type = 'all';

    var addCurrentUtcObj = moment(moment().tz(TIMEZONE_STR).add(1, 'minutes'));
    var addCurrentUtcDateObj = moment(moment().tz(TIMEZONE_STR).format('YYYY-MM-DD'));

    $('#smsEmailMessageAddForm #datepicker').datetimepicker({
        "locale": "en",
        "format":"MM/DD/YYYY",
        defaultDate: addCurrentUtcDateObj.format('YYYY-MM-DD'),
        minDate: addCurrentUtcDateObj.format('YYYY-MM-DD')
    }).on("dp.change",function (e) {
        
    });

    $('#time').combodate({
        firstItem: 'none',
        value: moment().tz(TIMEZONE_STR).add(1, 'minutes').format('HH:mm')
    });

    $(".hour").addClass('form-control');
    $(".minute").addClass('form-control');
    $(".ampm").addClass('form-control');

    $('#users_selection_type').on('change', function(event){
        users_selection_type = $(this).find(":selected").val();
        usersSelectionTypeChanged();
    });
    
    function usersSelectionTypeChanged(){
        if(users_selection_type == 'all'){
            $('#usersIdSection').addClass('hide');
            $('#debtorPhoneSection').addClass('hide');
            $('#users_id').addClass('ignore');
        } else if(users_selection_type == 'selected'){
            $('#usersIdSection').removeClass('hide');
            $('#debtorPhoneSection').removeClass('hide');
            if($("#message_type option:selected").val() == 'email') {
                $('#debtorPhoneSection').addClass('hide');
            }
            $('#users_id').removeClass('ignore');
        }
    }
    
    $('#message_type').on('change', function(event){
        usersSelectionTypeChanged();
    });

    $('#smsEmailMessageAddForm .sent_type').on('change', function() {
        if($(this).val() == 'schedule' ){
            $("#smsEmailMessageAddForm .dateblk").removeClass("hidden");
            $("#smsEmailMessageAddForm .timeblk").removeClass("hidden");
        }
        else{
            $("#smsEmailMessageAddForm .dateblk").addClass("hidden");
            $("#smsEmailMessageAddForm .timeblk").addClass("hidden");
        }
    });

    $("#smsEmailMessageAddForm").validate({
        ignore: '.ignore',
        errorElement: 'div',
        errorClass: 'text-danger text-left text-bold',
        rules: {
            message_type: {
                required: true,
            },
            users_selection_type: {
                required: true,
            },
            "users_id[]" : {
                required: { 
                    depends: function () { return $('#debtors_phone').val() == null }
                },
            }, 
            title: {
                required: true,
            },
            description: {
                required: true,
                maxlength: function() {
                    if($("#message_type").val() == 'sms') {
                        return 140;
                    }
                }
            },
            sent_type: {
                required: true
            },
            sentdate: {
                required: {
                    depends: function () { return $('input[name=sent_type]:checked').val() == 'schedule' }
                },
                addCheckUtcDate: {
                    depends: function () { return $('input[name=sent_type]:checked').val() == 'schedule' }
                }
            },
            senttime: {
                addCheckUtcTime: {
                    depends: function () { return $('input[name=sent_type]:checked').val() == 'schedule' }
                }
            }
        }, messages: {
        },
        highlight: function (element) {
            $(element).closest('.form-control').addClass('has-error');
        },
        unhighlight: function (element) {
            $(element).closest('.form-control').removeClass('has-error');
        },
        errorPlacement: function (error, element) {
            if (element.attr('name')=='senttime') {
                error.insertAfter($('.combodate'));
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function (form) {
            console.log('in submitHandler');
            $('#submitBtn').button('loading');
            submitAddsmsEmailMessageFormData();
        },
    });

    jQuery.validator.addMethod("addCheckUtcDate", function(value, element) {
        var valueArr = value.split('/');
        var currentUtcDate = moment(moment().tz(TIMEZONE_STR).format('YYYY-MM-DD'), 'YYYY-MM-DD');
        var scheduleUtcDate = moment(valueArr[2]+ '-' + valueArr[0] + '-' + valueArr[1], 'YYYY-MM-DD');
        var isValidScheduleDate = moment(currentUtcDate).isSameOrBefore(scheduleUtcDate);
		return  isValidScheduleDate;
	}, "Please select future date only.");

    jQuery.validator.addMethod("addCheckUtcTime", function(value, element) {
        if($('#smsEmailMessageAddForm #date').val() == ''){
            return false;
        }
        var addDate = moment($('#smsEmailMessageAddForm #date').val(), "MM/DD/YYYY").format("YYYY-MM-DD");
        var currentUtcDateTime = moment(moment().tz(TIMEZONE_STR).format('YYYY-MM-DD HH:mm'), 'YYYY-MM-DD HH:mm').add(1, 'minutes');
        var scheduleUtcDateTime = moment(addDate + ' ' + value , 'YYYY-MM-DD HH:mm');

        var isValidScheduleDate = moment(currentUtcDateTime).isSameOrBefore(scheduleUtcDateTime);
		return  isValidScheduleDate;
	}, function(params, element) {
        if($('#smsEmailMessageAddForm #date').val() == ''){
            return 'Please select date';
        }else{
            return 'Please select proper time';
        }
    });

    function submitAddsmsEmailMessageFormData() {
        $("#sentdatetime").val(moment($("#date").val() + ' '+ $("#time").val()).format('YYYY-MM-DD HH:mm:ss'));
        var fnToastErrorMsg = 'Something went wrong';
        $.ajax({
            type: "POST",
            url: "{{url('admin/sms-email-message')}}",
            dataType: 'json',
            data: $( "#smsEmailMessageAddForm" ).serialize(),
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            beforeSend: function () {
                
            },
            success: function (data) {
                if (data.status == true) {
                    window.location.href = "{{url('admin/sms-email-message')}}";
                } else {
                    $('#submitBtn').button('reset');
                    fnToastError(fnToastErrorMsg);
                }
            },
            error: function (xhr, status, error) {
                $('#submitBtn').button('reset');
                var errors = xhr.responseJSON;
                var errorsHtml= '';
                if (errors.errors) {
                    $.each( errors.errors, function( key, value ) {
                        errorsHtml += '<li>' + value[0] + '</li>'; 
                    });
                    fnToastError( errorsHtml);
                }
                else {
                    fnToastError(fnToastErrorMsg);
                }
            }
        });
    }

</script>
@endpush
