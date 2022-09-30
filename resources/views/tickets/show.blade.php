@extends('layouts.app')

@section('title', $ticket->title)

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 ticketblk">
            <div class="panel panel-default">
                <div class="panel-heading">
                    #{{ $ticket->ticket_id }} - {{ $ticket->title }}
                </div>

                <div class="panel-body">
                    {{--  @include('includes.flash')  --}}

                    <div class="ticket-info">
                        <p>{{ $ticket->message }}</p>
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

                    <div class="comments clearfix">@foreach ($comments as $comment)
                        @if($comment->user)
                        <div class="panel user-comment">
                            <div class="panel panel-heading">
                                <b class="pull-right">{{ $comment->user->firstname }}  {{ $comment->user->lastname }}</b>
                                <span>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $comment->created_at, 'UTC')->setTimezone(config('ethiopay.TIMEZONE_STR'))->format('Y-m-d H:i:s') }}</span>
                            </div>
                
                            <div class="panel panel-body">
                                <span class="pull-right">{!! $comment->comment !!}</span>
                            </div>
                        </div>                            
                        @elseif($comment->admin)
                        <div class="panel admin-comment">
                            <div class="panel panel-heading">
                                <b>{{ $comment->admin->name }}</b>
                                <span class="pull-right">{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $comment->created_at, 'UTC')->setTimezone(config('ethiopay.TIMEZONE_STR'))->format('Y-m-d H:i:s') }}</span>
                            </div>
                
                            <div class="panel panel-body">
                                {!! $comment->comment !!}     
                            </div>
                        </div>
                        @endif
                        @endforeach
                        <?php $count= count($comments) - 1; ?>
                        <input type="hidden" value='{{ isset($comments[$count]->id) ? ($comments[$count]->id) : ''}}' id="commentLastId">
                    </div>

                    <div class="comment-form">
                        <form class="form" id="postComment" action="javascript:void(0);">
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
                                <button type="submit" class="btn btn-primary submit-button" >Submit</button>
                                <a href="{{ url('tickets') }}" class="btn btn-primary">Back</a>                                
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
            comment : {
                required: function() {
                    return CKEDITOR.instances.comment.updateElement();
                }
            }
        },  highlight: function (element) {
    
        }, unhighlight: function (element) {
    
        },
        errorPlacement: function (error, element) {
            if (element.attr('name') == 'comment') {
                error.insertAfter($('#cke_comment'));
            } else {
                error.insertAfter(element);
            }
        },
        errorElement: 'span',
        errorClass: 'input_error',
        submitHandler: function (form) {
            $('.submit-button').button('loading');
            postComment();
        }
    });

    function postComment() {
        var fnToastErrorMsg = 'Something went wrong';
        $.ajax({
            type: "POST",
            url: "{{url('comment')}}",
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
            url: "{{ url('get-comment') }}/{{ $ticket->ticket_id }}",
            method: 'GET',
            data: { last_id : $("#commentLastId").val()},
            success: function(comment) {
                var comments = "";
                if(comment.data.length != '0') {
                    if(comment.data[0].id) {
                        $("#commentLastId").val(comment.data[comment.data.length - 1].id);
                        $.each(comment.data, function (index, value) {
                            if(value.user_id) {
                                comments += '<div class="panel user-comment" style="margin-bottom:0px;border:0px;"><div class="panel panel-heading" style="margin-bottom:0px;"><b class="pull-right"> '+ value.firstname +'  '+ value.lastname +
                                        '</b><span>' + value.createdDate +
                                        '</span></div><div class="panel panel-body"><span class="pull-right">' + value.comment +'</span></div></div>'
                            }
                            else {
                                comments += '<div class="panel admin-comment" style="margin-bottom:0px;border:0px;"><div class="panel panel-heading" style="margin-bottom:0px;"><b> '+ value.name +
                                        '</b><span class="pull-right">' + value.createdDate +
                                        '</span></div><div class="panel panel-body">' + value.comment +'</div></div>'
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