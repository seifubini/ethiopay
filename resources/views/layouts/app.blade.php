<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">        
        
        <title>{{ config('app.name') }} | @yield('title')</title>

        @include('layouts.include.app.cssfiles')
       	@stack('styles')

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        @include('layouts.include.app.header')
        @yield('content')        
        @include('layouts.include.app.footer')
        @include('layouts.include.app.jsfiles')
        @stack("scripts")
    </body>
</html>
