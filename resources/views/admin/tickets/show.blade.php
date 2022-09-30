@extends('admin.layout.admin')
@section('title', $ticket->title)

@section('content')
<div class="container commentPage">
    <div class="row">
        <div class="col-md-12 ticketblk ">
            <div class="panel panel-default">
                <div class="panel-heading">
                    #{{ $ticket->ticket_id }} - {{ $ticket->title }}
                </div>
                <div class="panel-body">
                    <div class="user-info row ckearfix">
                        <div class="col-sm-9">
                            <div class="row">
                                <div class="col-md-5">
                                    <h4> User Detail </h4>
                                    <p>Name : {{ $user->fullname }}</p>
                                    <p>Email : {{ $user->email }}</p>
                                    <p>Phone no : {{ $user->phone_code }}{{ $user->phone_number }}</p>
                                </div>
                                <div class="col-md-7">
                                    <h4> Ticket Detail </h4>
                                    <p>Title: {{ $ticket->message }}</p>
                                    <p>
                                        @if ($ticket->status === 'Open')
                                        Status: <span class="label label-success">{{ $ticket->status }}</span>
                                        @else
                                        Status: <span class="label label-danger">{{ $ticket->status }}</span>
                                        @endif
                                    </p>
                                    <p>Transaction Id: {{ $ticket->transaction_id }}</p>
                                    <p>Created on: {{ $ticket->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="proimg pull-right">
                                <img src="{{ $user->profile_picture_medium }}">
                            </div>
                        </div>
                    </div>
                    <div class="ticket-info row">

                    </div>
                    <div class="comments clearfix">
                        @foreach ($comments as $comment)
                        @if($comment->user)
                        <div class="panel user-comment">
                            <div class="panel panel-heading">
                                <b>{{ $comment->user->firstname }}  {{ $comment->user->lastname }}</b>
                                <span class="pull-right">{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $comment->created_at, 'UTC')->setTimezone(config('ethiopay.TIMEZONE_STR'))->format('Y-m-d H:i:s') }} </span>
                            </div>
                            <div class="panel panel-body">
                                {!! $comment->comment !!}     
                            </div>
                        </div>
                        @elseif($comment->admin)
                        <div class="panel admin-comment">
                            <div class="panel panel-heading">
                                <b class="pull-right">{{ $comment->admin->name }}</b>
                                <span>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $comment->created_at, 'UTC')->setTimezone(config('ethiopay.TIMEZONE_STR'))->format('Y-m-d H:i:s') }}</span>
                            </div>
                            <div class="panel panel-body">
                                <span class="pull-right">{!! $comment->comment !!}</span>
                            </div>
                        </div>
                        @endif
                        @endforeach
                        <?php $count = count($comments) - 1; ?>
                        <input type="hidden" value='{{ isset($comments[$count]->id) ? ($comments[$count]->id) : ''}}' id="commentLastId">
                    </div><hr>
                    <div class="comment-form">
                        <form action="javascript:void(0);" class="form" id="postComment">
                            {!! csrf_field() !!}
                            <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                            <div class="form-group{{ $errors->has('comment') ? ' has-error' : '' }}">
                                <textarea rows="10" id="comment" class="form-control" name="comment" placeholder="Write Your Comment Here"></textarea>
                                @if ($errors->has('comment'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('comment') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary submit-button">Submit</button>
                                <a href="{{ url('admin/tickets') }}" class="btn btn-danger center-button">Back</a>                                  
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push("scripts")
<script src="{{ asset('plugins/ckeditor-4/ckeditor.js') }}"></script>
<script type="text/javascript">
    CKEDITOR.replace( 'comment' );
    $("#postComment").validate({
        ignore: [],
        rules: {
            comment: {
                required: function() {
                    return CKEDITOR.instances.comment.updateElement();
                }
            },
        },
        errorPlacement: function (error, element) {
            if (element.attr('name') == 'comment') {
                error.insertAfter($('#cke_comment'));
            } else {
                error.insertAfter(element);
            }
        },
        highlight: function (element) {
            $(element).closest('.form-control').addClass('has-error');
        },
        unhighlight: function (element) {
            $(element).closest('.form-control').removeClass('has-error');
        },
        submitHandler: function (form) {
            $('.submit-button').button('loading');
            postComment();
        }
    });

    function postComment() {
        var fnToastErrorMsg = 'Something went wrong';
        $.ajax({
            type: "POST",
            url: "{{url('admin/comment')}}",
            dataType: 'json',
            data: $("#postComment").serialize(),
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            success: function (data) {
                if (data.status == true) {
                    CKEDITOR.instances.comment.setData("");
                    $('#postComment').trigger("reset");
                    $('.submit-button').button('reset');
                    fnToastSuccess(data.message);
                }
            },
            error: function (xhr, status, error) {
                fnToastError(fnToastErrorMsg);
            }
        });
    }

    var position = 'bottom';
    function comment(){
        $.ajax({
            url: "{{ url('admin/get-comment') }}/{{ $ticket->ticket_id }}",
            method: 'GET',
            data: { last_id : $("#commentLastId").val()},
            success: function(comment) {
                var comments = "";
                if(comment.data.length != '0') {
                    if(comment.data[0].id) {
                        $("#commentLastId").val(comment.data[comment.data.length - 1].id);
                        $.each(comment.data, function (index, value) {
                            if(value.user_id) {
                                comments += '<div class="panel user-comment"><div class="panel panel-heading"><b> '+ value.firstname +'  '+ value.lastname +
                                        '</b><span class="pull-right">' + value.createdDate +
                                        '</span></div><div class="panel panel-body">' + value.comment +'</div></div>'
                            }
                            else {
                                comments += '<div class="panel admin-comment"><div class="panel panel-heading"><b  class="pull-right"> '+ value.name +
                                        '</b><span>' + value.createdDate +
                                        '</span></div><div class="panel panel-body"><span class="pull-right">' + value.comment +'</span></div></div>'
                            }
                        });
                    }
                }
                $('.comments').append(comments);
                if (position == 'bottom')
                    $('.comments').scrollTop($('.comments')[0].scrollHeight);
            },
        });
    }
    comment();


    $('.comments').scrollTop($('.comments')[0].scrollHeight);
    var commentInterval = setInterval(function(){ comment() }, 3000);

    $('.comments').scroll(function() {
        if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
            position = 'bottom';
        }
        else {
            position = 'top';
        }
    });
</script>

@endpush