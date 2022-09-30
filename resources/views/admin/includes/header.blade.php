<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<meta http-equiv="X-UA-Compatible" content="IE=edge">
<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ config('app.name', 'Ethiopay') }} | @yield('title') - Admin</title>

<!--<link rel="icon" href="{{asset('admins/img/fav_icon.ico')}}" type="image/x-icon"/>
<link rel="shortcut icon" href="{{asset('admins/img/fav_icon.ico')}}" type="image/x-icon"/>-->

<link href="{{ asset('admins/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('admins/font-awesome/css/font-awesome.css') }}" rel="stylesheet">

<link href="{{asset('admins/css/animate.css')}}" rel="stylesheet">

<!-- Toastr style -->
<link href="{{ asset('plugins/toastr/toastr.min.css') }}" rel="stylesheet">

<link href="{{asset('admins/css/style.css')}}" rel="stylesheet">
<link href="{{asset('admins/css/custom.css')}}" rel="stylesheet">

<link href="{{asset('admins/font-awesome/css/font-awesome.css')}}" rel="stylesheet">

<link href="{{asset('plugins/chosen/chosen.css')}}" rel="stylesheet">

{{-- Data table --}}
<link href="{{ asset('plugins/dataTables/dataTables.bootstrap.css') }}" rel="stylesheet">
<link href="{{ asset('plugins/dataTables/dataTables.responsive.css') }}" rel="stylesheet">
<link href="{{ asset('plugins/dataTables/dataTables.tableTools.min.css') }}" rel="stylesheet">

{{-- sweetalert2 --}}
<link href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">

<!-- bootstrap-datetimepicker -->
<link rel="stylesheet" href="{{ asset('plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css') }}">

<!-- bootstrap-datepicker -->
<link rel="stylesheet" href="{{ asset('plugins/datepicker/datepicker3.css') }}">

<!-- time-to -->
<link rel="stylesheet" href="{{ asset('plugins/time-to/timeTo.css') }}">

<!-- select2 -->
<link rel="stylesheet" href="{{ asset('css/plugins/select2/select2.min.css') }}">

