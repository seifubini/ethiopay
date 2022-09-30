@extends('admin.layout.admin')
@section('title',"Admin-User Message Detail")

@section('pageHeading')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-12">
        <h2>@yield('title')</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{url('admin/dashboard')}}">Dashboard</a>
            </li>
            <li>
                <a href="{{url('admin/admin-user-message')}}">Admin-User Message</a>
            </li>
            <li>
                @yield('title')
            </li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="container commentPage">
    <div class="row">
        <div class="col-md-12 ticketblk ">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="user-info clearfix">
                        <div class="col-sm-9">
                            <h4> User Detail </h4>
                            <p>Name : {{ $user->fullname }}</p>
                            <p>Email : {{ $user->email }}</p>
                            <p>Phone no : {{ $user->phone_code }}{{ $user->phone_number }}</p>
                        </div>
                        <div class="col-sm-3">
                            <div class="proimg pull-right">
                                <img src="{{ $user->profile_picture_medium }}">
                            </div>
                        </div>
                    </div>

                    <div id="adminUserMessagesSection" class="comments clearfix">
                        @foreach ($adminUserMessages as $adminUserMessage)
                        @if($adminUserMessage->sent_by == 'user')
                        <div class="panel user-comment">
                            <div class="panel panel-heading">
                                <b>{{ $user->fullname }}</b>
                                <span class="pull-right">{{ $adminUserMessage->updated_at_timezone }} </span>
                            </div>

                            <div class="panel panel-body">
                                {!! $adminUserMessage->message !!}     
                            </div>
                        </div>
                        @elseif($adminUserMessage->sent_by == 'admin')
                        <div class="panel admin-comment">
                            <div class="panel panel-heading">
                                <b class="pull-right">{{ $adminUserMessage->adminData->name }}</b>
                                <span>{{ $adminUserMessage->updated_at_timezone }}</span>
                            </div>
                            <div class="panel panel-body">
                                <span class="pull-right">{!! $adminUserMessage->message !!}    </span>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>

                    <div class="comment-form">
                        <form id="adminUserMessageForm" name="adminUserMessageForm" action="javascript:void(0);" class="form">
                            <input id="last_message_id" name="last_message_id" type="hidden" value="{{ ($adminUserMessages->last() ? $adminUserMessages->last()->id : 0 ) }}">
                            <input id="user_id" name="user_id" type="hidden" value="{{ $user->id }}">
                            <div class="form-group">
                                <textarea id="message" name="message" class="form-control" rows="8" placeholder="Write Your Message Here"></textarea>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary submit-button">Submit</button>
                                <a href="{{ url('admin/admin-user-message') }}" class="btn btn-danger center-button">Back</a>                                  
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    var last_message_id = {{ ($adminUserMessages->last() ? $adminUserMessages->last()->id : 0 ) }};
    $("#adminUserMessageForm").validate({
        rules: {
            message: {
                required: true
            },
        },
        errorPlacement: function (error, element) {
            error.insertAfter(element);
        },
        highlight: function (element) {
            $(element).closest('.form-control').addClass('has-error');
        },
        unhighlight: function (element) {
            $(element).closest('.form-control').removeClass('has-error');
        },
        submitHandler: function (form) {
            $('.submit-button').button('loading');
            adminUserMessageFormSubmit();
        }
    });
    var position = 'bottom';

    function adminUserMessageFormSubmit() {
        var fnToastErrorMsg = 'Something went wrong';
        $.ajax({
            type: "POST",
            url: "{{url('admin/admin-user-message')}}",
            dataType: 'json',
            data: $("#adminUserMessageForm").serialize(),
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            success: function (sendMessageResponse) {
                $('.submit-button').button('reset');
                if (sendMessageResponse.status == true) {
                    $('#adminUserMessageForm').trigger("reset");
                    fnToastSuccess(sendMessageResponse.message);
                } else {
                    fnToastError(sendMessageResponse.message);
                }
            },
            error: function (xhr, status, error) {
                $('.submit-button').button('reset');
                fnToastError(fnToastErrorMsg);
            }
        });
    }
    
    function loadAdminUserMessages() {
        $.ajax({
            type: "GET",
            url: "{{url('admin/admin-user-message/load-new-messages')}}",
            dataType: 'json',
            data: {
                user_id: {{ $user->id }},
                last_message_id: last_message_id
            },
            success: function (loadNewMessagesResponse) {
                if (loadNewMessagesResponse.status == true) {
                    var adminUserMessages = loadNewMessagesResponse.adminUserMessages;                    
                    var adminUserMessagesLength = adminUserMessages.length;
                    var adminUserMessagesLtml = '';
                    
                    if(adminUserMessagesLength > 0){
                        for(var i = 0; i < adminUserMessagesLength; i++){
                            var adminUserMessage = adminUserMessages[i];
                            if(adminUserMessage.sent_by == 'user'){
                                adminUserMessagesLtml += '<div class="panel user-comment">';
                                    adminUserMessagesLtml += '<div class="panel panel-heading">';
                                        adminUserMessagesLtml += '<b>{{ $user->fullname }}</b>';
                                        adminUserMessagesLtml += '<span class="pull-right">' + adminUserMessage.updated_at_timezone + '</span>';
                                    adminUserMessagesLtml += '</div>';
                                    adminUserMessagesLtml += '<div class="panel panel-body">';
                                        adminUserMessagesLtml += adminUserMessage.message;
                                    adminUserMessagesLtml += '</div>';
                                adminUserMessagesLtml += '</div>';
                            } else if(adminUserMessage.sent_by == 'admin'){
                                adminUserMessagesLtml += '<div class="panel admin-comment">';
                                    adminUserMessagesLtml += '<div class="panel panel-heading">';
                                        adminUserMessagesLtml += '<b class="pull-right">' + adminUserMessage.admin_data.name + '</b>';
                                        adminUserMessagesLtml += '<span>' + adminUserMessage.updated_at_timezone + '</span>';
                                    adminUserMessagesLtml += '</div>';
                                    adminUserMessagesLtml += '<div class="panel panel-body">';
                                        adminUserMessagesLtml += '<span class="pull-right">' + adminUserMessage.message + '</span>';
                                    adminUserMessagesLtml += '</div>';
                                adminUserMessagesLtml += '</div>';
                            }
                        }
                        last_message_id = adminUserMessages[adminUserMessagesLength-1].id;
                        $('#adminUserMessagesSection').append(adminUserMessagesLtml);
                        if (position == 'bottom')
                            $('#adminUserMessagesSection').scrollTop($('#adminUserMessagesSection')[0].scrollHeight);
                        
                        {{--  $('#adminUserMessagesSection').animate({
                            scrollBottom: $('#adminUserMessagesSection')[0].scrollHeight
                        }, 1000);  --}}
                    }                    
                    setTimeout(loadAdminUserMessages, 3000);
                }
            },
            error: function (xhr, status, error) {
                
            }
        });
    }
    
    $('#adminUserMessagesSection').scrollTop($('#adminUserMessagesSection')[0].scrollHeight);
//    $('#adminUserMessagesSection').animate({
//        scrollTop: $("#adminUserMessagesSection").offset().top + 100
//    }, 1);
    setTimeout(loadAdminUserMessages, 3000);

    $('#adminUserMessagesSection').scroll(function() {
        if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
            position = 'bottom';
        }
        else {
            position = 'top';
        }
    });
</script>            
@endpush