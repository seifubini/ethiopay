<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name') }} | @yield('title')</title>

        <link href="{{ asset('admins/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('admins/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
        <link href="{{ asset('admins/css/animate.css') }}" rel="stylesheet">
        <link href="{{ asset('admins/css/style.css') }}" rel="stylesheet">
        <link href="{{ asset('admins/css/custom.css') }}" rel="stylesheet">
        <!-- Toastr style -->
        <link href="{{ asset('plugins/toastr/toastr.min.css') }}" rel="stylesheet">
        
        <script>
            window.Laravel = <?php echo json_encode(['csrfToken' => csrf_token()]); ?>
        </script>
    </head>
    <body class="gray-bg">
        @yield('content')
        
        <!-- Mainly scripts -->
        <script src="{{ asset('admins/js/jquery-2.1.1.js') }}"></script>
        <script src="{{ asset('admins/js/bootstrap.min.js') }}"></script>
        
        <!-- Scripts -->
        <script src="{{asset('admins/js/app.js') }}"></script>
        
        <!-- valiation-->
        <script src="{{asset('plugins/validate/jquery.validate.min.js')}}"></script>

        <!-- Toastr -->
        <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>


        <script type="text/javascript">
            $(document).find('input').each(function () {
                var currentInputVal = $(this).val();
                if (currentInputVal) {
                    $(this).parent().addClass('activefield');
                }
            });

            $(document).on('keyup keypress blur change focus', 'input', function (event) {
                var eventType = event.type;
                if (eventType == 'focusin' || eventType == 'keyup') {
                    $(this).parent().addClass('activefield');
                } else {
                    var currentInputVal = $(this).val();
                    if (currentInputVal) {
                        $(this).parent().addClass('activefield');
                    } else {
                        $(this).parent().removeClass('activefield');
                    }
                }
            });

            $(document).on('click', '.hideShowPassword', function (event) {
                var passwordInputFiledType = $(this).siblings("input").attr('type');
                if (passwordInputFiledType == 'password') {
                    $(this).siblings("input").attr('type', 'text');
                    $(this).addClass('active');
                } else {
                    $(this).siblings("input").attr('type', 'password');
                    $(this).removeClass('active');
                }
            });

            function fnToastSuccess(message) {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    showMethod: 'slideDown',
                    timeOut: 10000
                };
                toastr.success(message);
            }
            function fnToastError(message) {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    showMethod: 'slideDown',
                    timeOut: 10000
                };
                toastr.error(message);
            }
            {{-- //Alert Message --}}
            @if(\Session::has('success'))
                fnToastSuccess("{{ \Session::get('success') }}");
            @endif
            {{-- //Error Message --}}
            @if(\Session::has('error'))
                fnToastError("{{ \Session::get('error') }}");
            @endif

        </script>
        @stack('scripts')
    </body>
</html>