<!DOCTYPE html>
<html>
    <head>
        @include('admin.includes.header')
        @stack('styles')
        <script>
            window.Laravel = <?php echo json_encode([ 'csrfToken' => csrf_token()]); ?>
        </script>
    </head>
    <body class="pace-done">
        <div id="wrapper">
            {{-- @include('admin.sessionPopup') --}}
            @include('admin.includes.sidebar')
            <div id="page-wrapper" class="gray-bg">
                @include('admin.includes.topbar')
                @yield('pageHeading')
                @yield('content')
                <div class="footer">
                    <div>
                        <strong> {{ "©Copyright ".date("Y") }} </strong> {{ config('app.name', 'Ethiopay') }}
                    </div>
                </div>
            </div>
        </div>
        @include('admin.includes.footer')
        {{-- @include('admin.includes.expiredpopupjs') --}}
        @stack('scripts')
    </body>
</html>