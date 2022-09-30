@extends('layouts.app')

@section('title', 'Open Ticket')

@section('content')
    {{--  <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Open New Ticket</div>

                <div class="panel-body">
                    @include('includes.flash')

                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/tickets') }}" id="createTicket_form">
                        {!! csrf_field() !!}

                        <div class="form-group{{ $errors->has('transaction_id') ? ' has-error' : '' }}">
                            <label for="transaction_id" class="col-md-4 control-label">Transaction ID</label>

                            <div class="col-md-6">
                                <input id="transaction_id" type="text" class="form-control" name="transaction_id" value="{{ old('transaction_id') }}">

                                @if ($errors->has('title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('transaction_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                            <label for="title" class="col-md-4 control-label">Title</label>

                            <div class="col-md-6">
                                <input id="title" type="text" class="form-control" name="title" value="{{ old('title') }}">

                                @if ($errors->has('title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('message') ? ' has-error' : '' }}">
                            <label for="message" class="col-md-4 control-label">Message</label>

                            <div class="col-md-6">
                                <textarea rows="10" id="message" class="form-control" name="message"></textarea>

                                @if ($errors->has('message'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('message') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-ticket"></i> Open Ticket
                                </button>
                                <a href="{{ url('tickets') }}" class="btn btn-primary">Back</a>  
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>  --}}
    <main class="ep-tabular-format">
        <section>
            <div class="container">
                <div class="tabular-main">
                    <div class="tab-content">
                        <div class="tab-content-detail">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="payment-info">
                                        <i class="utility-img"></i>
                                        <h3>Support</h3>
                                        <p class="title-content">Please provide as many details as possible about your concern, ID, Type and the user.</p>
                                        {{--  <button type="button" class="btn btn-default">VIEW EXAMPLE</button>  --}}
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/tickets') }}" id="createTicket_form">
                                        {!! csrf_field() !!}
                                        <div class="customer-form clearfix">
                                            <h4>New Support Ticket</h4>
                                            <div class="input-box">
                                                <div class="{{ $errors->has('transaction_id') ? ' has-error' : '' }}">
                                                        <h5>Transaction Id</h5>
                                                        <input type="text" name="transaction_id" id="transaction_id" placeholder="Type Here" value="{{ old('transaction_id') }}">
                                                        <span id="transaction_id_checking" class="hide">Please wait...</span> 
                                                        @if ($errors->has('transaction_id'))
                                                        <span class="help-block">
                                                            <strong>{{ $errors->first('transaction_id') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="{{ $errors->has('title') ? ' has-error' : '' }}">
                                                        <h5>Title</h5>
                                                        <input type="text" name="title" id="title" placeholder="Type Here" value="{{ old('title') }}">
                                                        @if ($errors->has('title'))
                                                        <span class="help-block">
                                                            <strong>{{ $errors->first('title') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="{{ $errors->has('message') ? ' has-error' : '' }}">
                                                        <h5>Message</h5>
                                                        <textarea name="message" id="message" placeholder="Type Here">{{ old('message') }}</textarea>
                                                        @if ($errors->has('message'))
                                                        <span class="help-block">
                                                            <strong>{{ $errors->first('message') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                                {{--  <h5>Title</h5>
                                                <input type="text" name="title" id="title" placeholder="Type Here">
                                                <h5>Message</h5>
                                                <textarea placeholder="Type Here" name="message" id="message"></textarea>  --}}
                                            </div>
                                            <button type="submit" class="btn btn-default">Continue</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

@push("scripts")
<script type="text/javascript">

    $("#createTicket_form").validate({
        rules: {
            title: {
                required: true,
            },
            message: {
                required: true,
            },
            transaction_id: {
                required: true,
                remote: {
                    url: "{{url('tickets/validateTransactionId')}}",
                    type: "GET",
                    dataType: 'json',
                    async: false,
                    beforeSend: function ( ) {
                        $("#transaction_id_checking").removeClass('hide');
                    },
                    data: {
                        transaction_id: function () {
                            return $("#transaction_id").val();
                        },
                    },
                    error: function (xhr, status, error) {
                        $("#transaction_id_checking").addClass('hide');
                    },
                    dataFilter: function (data) {
                        var data = JSON.parse(data);
                        $("#transaction_id_checking").addClass('hide');
                        if (data.status == 'true') {
                            return '"true"';
                        }
                        return '"' + data.message + '"';
                    }
                },
            },
        },
    });
</script>

@endpush