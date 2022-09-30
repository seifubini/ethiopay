<!--jquery-->
<script type="text/javascript" src="{{ asset('js/plugins/jquery/jquery.js') }}"></script>

<!-- Include all compiled plugins (below), or include individual files as needed -->
<script type="text/javascript" src="{{ asset('js/plugins/bootstrap/bootstrap.min.js') }}"></script>

<!-- jquery Validate -->
<script type="text/javascript" src="{{ asset('js/plugins/validation/jquery.validate.min.js') }}"></script>
<!--jquery Validate Additional Methods-->
<script type="text/javascript" src="{{ asset('js/plugins/validation/additional-methods.min.js') }}"></script>

<!-- Toastr -->
<script type="text/javascript" src="{{asset('js/plugins/toastr/toastr.min.js')}}"></script>

{{-- Sweetalert2 --}}
<script src="{{ asset('plugins/sweetalert2/es6-promise.auto.min.js') }} "></script>
<!-- for IE support -->
<script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }} "></script> 

<!--select2-->
<script type="text/javascript" src="{{ asset('js/plugins/select2/select2.min.js') }}"></script>

<script type="text/javascript">
    window.Laravel = <?php echo json_encode(['csrfToken' => csrf_token()]); ?>
    
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
    @if(session('successAlert'))
        fnToastSuccess("{{ session('successAlert') }}");
    @endif

    {{-- //Error Message --}}
    @if(session('errorAlert'))
        fnToastError("{{ session('errorAlert') }}");
    @endif
    
    @if (session('status'))
        fnToastSuccess("{{ session('status') }}");
    @endif
    
    $(document).find('input').each(function() {
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
</script>