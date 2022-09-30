@extends('layouts.app')
@section("title", "Add Payment Method")

@section('content')
<main class="wallet-new-page">
    <div class="container">
        <h3>My Wallet - Add New</h3>
        <form id="addPaymentMethodForm" name="addPaymentMethodForm" action="javascript:void(0)">
            <div class="row">
                <div class="col-md-12">
                    <div class="eth-card">
                        <div class="heading-wrapper">
                            <div class="radio">
                                <label class="card-title">
                                    <input id="paymentMethodType_card" name="paymentMethodType" type="radio" value="card" checked="checked">Credit Card
                                    <span class="eth-radio"></span>
                                </label>
                            </div>
                            <div class="card-types">
                                <img src="{{ asset('img/credit-cards_amex.png') }}" alt="">
                                <img src="{{ asset('img/credit-cards_mastercard.png') }}" alt="">
                                <img src="{{ asset('img/credit-cards_visa.png') }}" alt="">
                            </div>
                        </div>
                        <p>Safe money transfer using your bank account. Visa, Maestro, Discover, American Express.</p>
                        <div class="form-wrapper">
                            <input id="stripe_token" name="stripe_token" type="hidden">
                            <div class="form-group">
                                <label for="name_on_card">NAME ON CARD</label>
                                <input id="name_on_card" name="name_on_card" type="text" value=""> <!-- Milan Katariya -->
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="card_number">CARD NUMBER</label>
                                        <input id="card_number" name="card_number" type="text" class="card-num" value="" placeholder="0000 0000 0000 0000"> <!-- 4000056655665556 -->
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="card_exp_date">EXPIRY DATE</label>
                                        <input id="card_exp_date" name="card_exp_date" type="text" class="card-exp" value="" placeholder="MM / YY"> <!-- 05/20 -->
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="card_cvc">CVV CODE</label>
                                        <input id="card_cvc" name="card_cvc" type="text" value="" placeholder="3 or 4 Digits"> <!-- 556 -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<!--            <div class="row">
                <div class="col-md-12">
                    <div class="eth-card">
                        <div class="radio">
                            <label class="card-title">
                                <input id="paymentMethodType_paypal" name="paymentMethodType" type="radio" value="paypal" disabled="">PayPal
                                <span class="eth-radio"></span>
                            </label>
                        </div>
                        <div class="card-types">
                            <img src="{{ asset('img/paypal.png') }}" alt="">
                        </div>
                        <p>You will be redirected to PayPal website to complete the process securely.</p>
                    </div>
                </div>
            </div>-->
            <div id="errorContainer">

            </div>
            <div class="row">
                <div class="col-md-12">
                    <button class="cntbtn" id="addPaymentMethodForm_submitBtn" name="addPaymentMethodForm_submitBtn" type="submit">CONTINUE</button>
                </div>
            </div>
        </form>
        <span id="card-element"></span>
    </div>
</main>
@endsection

@push("scripts")
<!-- Stripe JS -->
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>

<script type="text/javascript" src="{{ asset('plugins/cleave/cleave.min.js') }}"></script>
<!--<script src="https://js.stripe.com/v3/"></script>-->

<script type="text/javascript">
Stripe.setPublishableKey("{{env('STRIPE_PUBLISHABLE_KEY')}}");

var cleaveCardNum = new Cleave('.card-num', {
    creditCard: true,
    onCreditCardTypeChanged: function (type) {
    }
});

var cleaveCardExp = new Cleave('.card-exp', {
    date: true,
    datePattern: ['m', 'y']
});

$("#addPaymentMethodForm").validate({
    rules: {
        paymentMethodType: {
            required: true,
        },
        name_on_card: {
            required: function () {
                var paymentMethodType = $('input[name="paymentMethodType"]:checked').val();
                if (paymentMethodType == 'card') {
                    return true;
                }
                return false;
            },
        },
        card_number: {
            required: function () {
                var paymentMethodType = $('input[name="paymentMethodType"]:checked').val();
                if (paymentMethodType == 'card') {
                    return true;
                }
                return false;
            },
            {{-- number: true, --}}
            minlength: 14,
            creditcard: true
        },
        card_exp_date: {
            required: function () {
                var paymentMethodType = $('input[name="paymentMethodType"]:checked').val();
                if (paymentMethodType == 'card') {
                    return true;
                }
                return false;
            },
            validation_rule_card_exp_date: true
        },
        card_cvc: {
            required: function () {
                var paymentMethodType = $('input[name="paymentMethodType"]:checked').val();
                if (paymentMethodType == 'card') {
                    return true;
                }
                return false;
            },
            number: true,
            minlength: 3,
            maxlength: 4,
        },
    }, messages: {
        paymentMethodType: {
            required: 'Please select payment method',
        },
    }, errorPlacement: function (error, element) {
        if (element.attr('name') == 'paymentMethodType') {
            error.insertAfter($('#errorContainer'));
        } else if (element.attr('name') == 'serviceProvider_id') {
            error.insertAfter($('#serviceProvider_id').siblings(".select2"));
        } else {
            error.insertAfter(element);
        }

        //error.insertAfter(element.parent());
        //error.insertAfter(element);
    }, highlight: function (element) {

    }, unhighlight: function (element) {

    },
    errorElement: 'span',
    errorClass: 'input_error',
    submitHandler: function (form) {
        $('#addPaymentMethodForm_submitBtn').button('loading');
        var paymentMethodType = $('#addPaymentMethodForm input[name=paymentMethodType]:checked').val();
        if (paymentMethodType == 'card') {
            var card_exp_date = $('#card_exp_date').val();
            var card_exp_date_arr = card_exp_date.split("/");
            var card_exp_month = card_exp_date_arr[0];
            var card_exp_year = card_exp_date_arr[1];

            Stripe.createToken({
                name: $('#name_on_card').val(),
                number: $('#card_number').val(),
                exp_month: card_exp_month,
                exp_year: card_exp_year,
                cvc: $('#card_cvc').val()
            }, stripeGenerateCardTokenResponseHandler);
        } else if (paymentMethodType == 'paypal') {

        } else {

        }
    },
});

function stripeGenerateCardTokenResponseHandler(status, response) {
    if (response.error) {
        $('#addPaymentMethodForm_submitBtn').button('reset');
        //console.log(response.error.message);
        fnToastError(response.error.message);
    } else {
        var stripeToken = response['id'];
        $('#stripe_token').val(stripeToken);
        submitAddPaymentFormData();
    }
}

function submitAddPaymentFormData() {
    var addPaymentMethodFormSerialize = $("#addPaymentMethodForm input[name!=name_on_card][name!=card_number][name!=card_exp_date][name!=card_cvc]").serialize();
    var fnToastErrorMsg = 'Something went wrong';
    $.ajax({
        type: "POST",
        url: "{{url('payment-methods')}}",
        dataType: 'json',
        data: addPaymentMethodFormSerialize,
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        beforeSend: function () {
            //$('#addPaymentMethodForm_submitBtn').button('loading');
        },
        success: function (data) {
            if (data.status == true) {
                window.location.href = "{{url('payment-methods')}}";
            } else {
                $('#addPaymentMethodForm_submitBtn').button('reset');
                fnToastError(data.message);
            }
        },
        error: function (xhr, status, error) {
            $('#addPaymentMethodForm_submitBtn').button('reset')
            fnToastError(fnToastErrorMsg);
        }
    });
}

jQuery.validator.addMethod("validation_rule_card_exp_date", function (value, element) {
    var regExp = /(?:0[1-9]|1[0-2])\/(\d{2})/;
    return this.optional(element) || regExp.test(value);
}, "Please enter valid date.");
</script>
@endpush