
@extends('admin.layout.admin')

@push('styles')
@endpush

@section('title',"Add Payment Method")

@section('pageHeading')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-12">
        <h2>@yield('title')</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{url('admin/dashboard')}}">Dashboard</a>
            </li>
            <li>
                <a href="{{ url("admin/users") }}">Users</a>
            </li>
            <li>
                <a href="{{ url("admin/users/$user->id") }}">{{ $user->fullname }}</a>
            </li>
            <li>
                <a href="{{ url("admin/users/$user->id/payment-methods") }}">Payment Methods</a>
            </li>
            <li>
                @yield('title')
            </li>
        </ol>
    </div>
</div>

@endsection

@section('content')

@include('admin.user.menuLinks')
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5> @yield('title')</h5>
                </div>
                <div class="ibox-content">
                    <form id="addPaymentMethodForm" name="addPaymentMethodForm" class="form-horizontal" action="javascript:void(0)">
                        <div class="form-group">
                            <label for="paymentMethodType" class="control-label col-lg-3 required">Credit Card</label>
                            <div class="col-lg-6">
                                <input id="paymentMethodType_card" name="paymentMethodType" type="radio" value="card" checked="checked">Safe money transfer using your bank account. Visa, Maestro, Discover, American Express.
                                @if($errors)
                                <label class="text-danger" for="paymentMethodType">{{ ($errors->first('paymentMethodType')) ? $errors->first('paymentMethodType') : '' }}</label>
                                @endif
                            </div>
                        </div>
                        <input id="stripe_token" name="stripe_token" type="hidden">
                        <div class="form-group">
                            <label for="name_on_card" class="control-label col-lg-3 required">Name on Card</label>
                            <div class="col-lg-6">
                                <input id="name_on_card" name="name_on_card" type="text" value="" class="form-control">
                                @if($errors)
                                <label class="text-danger" for="name_on_card">{{ ($errors->first('name_on_card')) ? $errors->first('name_on_card') : '' }}</label>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="card_number" class="control-label col-lg-3 required">Card Number</label>
                            <div class="col-lg-6">
                                <input id="card_number" name="card_number" type="text" class="card-num form-control" value="" placeholder="0000 0000 0000 0000"> <!-- 4000056655665556 -->                                
                                @if($errors)
                                <label class="text-danger" for="card_number">{{ ($errors->first('card_number')) ? $errors->first('card_number') : '' }}</label>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="card_exp_date" class="control-label col-lg-3 required">Expiry Date</label>
                            <div class="col-lg-6">
                                <input id="card_exp_date" name="card_exp_date" type="text" class="card-exp form-control" value="" placeholder="MM / YY"> <!-- 05/20 -->
                                @if($errors)
                                <label class="text-danger" for="card_exp_date">{{ ($errors->first('card_exp_date')) ? $errors->first('card_exp_date') : '' }}</label>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="card_cvc" class="control-label col-lg-3 required">CVV Code</label>
                            <div class="col-lg-6">
                                 <input id="card_cvc" name="card_cvc" type="text" value="" placeholder="3 or 4 Digits" class="form-control"> <!-- 556 -->
                                @if($errors)
                                <label class="text-danger" for="card_cvc">{{ ($errors->first('card_cvc')) ? $errors->first('card_cvc') : '' }}</label>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-offset-3 col-lg-10">
                                <button class="btn btn-primary submit-button" id="addPaymentMethodForm_submitBtn" name="addPaymentMethodForm_submitBtn" type="submit">Submit</button>
                                <a href="{{ url("admin/users/$user->id/payment-methods") }}" class="btn btn-danger">Cancel</a>                        
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push("scripts")
<!-- Stripe JS -->
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<!--<script src="https://js.stripe.com/v3/"></script>-->

<script type="text/javascript">
Stripe.setPublishableKey("{{env('STRIPE_PUBLISHABLE_KEY')}}");

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
            number: true,
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
    }, 
    highlight: function (element) {
        $(element).closest('.form-control').addClass('has-error');
    },
    unhighlight: function (element) {
        $(element).closest('.form-control').removeClass('has-error');
    },
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
        url: "{{ url("admin/users/$user->id/payment-methods") }}",
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
                window.location.href = "{{ url("admin/users/$user->id/payment-methods") }}";
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
