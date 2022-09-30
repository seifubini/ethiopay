@extends('admin.layout.admin')
@section('title', 'Activity Log View')

@section('pageHeading')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-8">
        <h2>@yield('title')</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{url('admin/dashboard')}}">Dashboard</a>
            </li>
            <li>
                <a href="{{url('admin/activity-log')}}">Activity Log</a>
            </li>
            <li class="active">
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
                    <div class="user-info row ckearfix">
                        <div class="col-sm-9">
                            <div class="row">
                                <div class="col-md-5">
                                    <h4> Admin Detail </h4>
                                    <p>Name : {{ $admin->name }}</p>
                                    <p>Email : {{ $admin->email }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="log-info">
                        <div class="col-sm-12">
                            <div class="row">
                                <div>
                                    <h4>Activity Log Info</h4>
                                    <div class="form-group clearfix">
                                        <div class="col-lg-3">Subject</div>
                                        <div class="col-lg-9">
                                            {{ $activityLog->subject }}
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
                                        <div class="col-lg-3">Type</div>
                                        <div class="col-lg-9">
                                            {{ $activityLog->type }}
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
                                        <div class="col-lg-3">Ip</div>
                                        <div class="col-lg-9">
                                            {{ $activityLog->ip }}
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
                                        <div class="col-lg-3">Method</div>
                                        <div class="col-lg-9">
                                            {{ $activityLog->method }}
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
                                        <div class="col-lg-3">Activity created Time</div>
                                        <div class="col-lg-9">
                                            {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $activityLog->created_at, 'UTC')->setTimezone(config('ethiopay.TIMEZONE_STR'))->format('Y-m-d H:i:s') }}
                                        </div>
                                    </div>
                                    @if($activityLog->type == 'uid_lookups')
                                        <table width="100%" border="1">
                                            <thead>
                                                <th>service_type_id</th>
                                                <th>uid</th>
                                                <th>debtor_firstname</th>
                                                <th>debtor_lastname</th>
                                                <th>debtor_city</th>
                                                <th>amount</th>
                                                <th>cut_off_date</th>
                                            </thead>
                                            <tbody>
                                                @foreach($logInfo as $log)
                                                <tr>
                                                    @foreach((array)$log as $logInfoName=>$logInfoValue)
                                                    <td> {{ $logInfoValue }} </td>
                                                    @endforeach
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        @foreach($logInfo as $logInfoName => $logInfoValue)
                                            <div class="form-group clearfix">
                                                <div class="col-lg-3">{{ ucfirst(str_replace('_', ' ', $logInfoName)) }}</div>
                                                <div class="col-lg-9">
                                                        {!! $logInfoValue !!}
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
