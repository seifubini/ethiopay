@extends('layouts.app')
@section("title", "Wallet")

@section('content')
<main>
    <section class="wallet">
        <div class="container">
            <h3>My Wallet</h3>
            <div class="row wallet-section-row">
                @foreach ($paymentMethods as $paymentMethod)
                    <div class="col-md-6">
                        <div class="wallet_block ">
                            <div class="wb_content">
                            <div class="delete-btn" data-id="{{ $paymentMethod->id }}">Delete</div>
                                @if($paymentMethod->method_type == 'card')
                                    <a href=""></a>
                                    <h2>{{ $paymentMethod->name_on_card }}</h2>
                                    <p>Verified</p>
                                    <p> XXXX-<span class="bold">{{ $paymentMethod->card_number }}</span></p>
                                @elseif($paymentMethod->method_type == 'paypal')
                                    <a href=""></a>
                                    <h2>{{ $paymentMethod->paypal_email }}/h2>
                                    <p>Paypal Account</p>
                                    <!--<p>Verified</p>-->
                                @endif
                            </div>
                            <div class="img_last"><img src="{{ $paymentMethod->payment_method_icon }}" alt="visa"></div>
                        </div>
                    </div>
                @endforeach
<!--                <div class="col-md-6">
                    <div class="wallet_block ">
                        <div class="wb_content">
                            <a href=""></a>
                            <h2>HSBC USA</h2>
                            <p>Checking Account</p>
                            <p> XXXX-<span class="bold">8041</span></p>
                        </div>
                        <div class="img_last"><img src="{{ asset('img/visa.png') }}" alt="visa"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="wallet_block ">
                        <div class="wb_content">
                            <a href=""></a>
                            <h2>Bank of America</h2>
                            <p>Credit Card</p>
                            <p> XXXX-<span class="bold">8041</span></p>
                        </div>
                        <div class="img_last"><img src="{{ asset('img/mastercard.png') }}" alt="visa"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="wallet_block ">
                        <div class="wb_content">
                            <a href=""></a>
                            <h2>Alex@t1factory.com</h2>
                            <p>Paypal Account</p>
                            <p>Verified</p>
                        </div>
                        <div class="img_last"><img src="{{ asset('img/paypal.png') }}" alt="visa"></div>
                    </div>
                </div>-->
                <div class="col-md-6 last">
                    <div class="wallet_block last">
                        <div class="add_new">
                            <a href="{{ url('payment-methods/create') }}"><img src="{{ asset('img/add_new.svg') }}" alt="add_new"></a>
                            <h1>Add new</h1>
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
    $('.delete-btn').on('click', function(event) {
        var id = $(this).data('id');
        console.log(id);
        var status = $(this).val();
        var newstatus = $(this).data('newstatus');
        var alert_message = "Are you want to delete this card?";
        var alert_success_message = "Card deleted successfully!";

        if(id != "") {
            swal({
                title: "Are you sure?",
                text: alert_message,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, Delete it!",
                cancelButtonText: "No, cancel!",
                showLoaderOnConfirm: true,
                allowOutsideClick:false,
                allowEscapeKey:false,
                preConfirm: function (email) {
                    return new Promise(function (resolve, reject) {
                        setTimeout(function() {
                            jQuery.ajax({
                                url: "{{ url('payment-methods') }}/" + id ,
                                dataType: 'json',
                                type: 'DELETE',
                                data: {
                                    "_token": window.Laravel.csrfToken
                                },
                                success: function (result) {
                                    swal("success!", alert_success_message, "success");
                                    $(".swal2-styled").click(function() {
                                        location.reload();
                                    });
                                    {{--  location.reload();  --}}
                                    fnToastSuccess(alert_success_message);
                                },
                                error: function (xhr, status, error) {
                                    console.log(xhr);
                                    if(xhr.responseJSON && xhr.responseJSON.message!=""){
                                        swal("ohh snap!", xhr.responseJSON.message, "error");
                                    } else {
                                        swal("ohh snap!", "Something went wrong", "error");
                                    }
                                    ajaxError(xhr, status, error);
                                }
                            });
                        }, 0)
                    })
                },
            })
        }
    });
</script>

@endpush