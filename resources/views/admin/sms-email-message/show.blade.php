@extends('admin.layout.admin')

@section('title',"SMS-Email Message View")

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
                    <h5> @yield('title')</h5>
                </div>
                <div class="ibox-content">
                    <div id="printTransactionSection">
                        <div class="form-group clearfix">
                            <label class="control-label col-lg-3">Title</label>
                            <div class="col-lg-9">
                                {{ $smsEmailMessage->title }}
                            </div>
                        </div>  
                        <div class="form-group clearfix">
                            <label class="control-label col-lg-3">Description</label>
                            <div class="col-lg-9">
                                {{ $smsEmailMessage->description }}
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label class="control-label col-lg-3">Message Type</label>
                            <div class="col-lg-9">
                                {{ ucfirst($smsEmailMessage->message_type) }}
                            </div>
                        </div>     
                        <div class="form-group clearfix">
                            <label class="control-label col-lg-3">Sent Status</label>
                            <div class="col-lg-9">
                                {{ $smsEmailMessage->sentStatus }}
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label class="control-label col-lg-3">Sent DateTime</label>
                            <div class="col-lg-9">
                                {{ $smsEmailMessage->sentDate }}
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label class="control-label col-lg-3">Payours</label>
                            <div class="col-lg-9">
                                {{ $smsEmailMessage->payoursName }}
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label class="control-label col-lg-3">Debtors</label>
                            <div class="col-lg-9">
                                {{ $smsEmailMessage->debtorsName }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push("scripts")
@endpush