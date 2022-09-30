@extends('layouts.app')
@section("title", "PayBill Customer-Info")

@section('content')
<main class="ep-tabular-format">
    <section id="payBillMainSection">
        <div class="container">
            <div class="tabular-main">
                <form name="payBillForm" id="payBillForm" action="javascript:void(0);">
                    <div class="tabs-main">
                        <ul class="nav nav-tabs tabs">
                            <li class="active"><a class="payBillForm_nextBtn_nav" data-toggle="tab" href="#customer-info" data-step="1">01 CUSTOMER INFO</a></li>
                            <li class=""><a class="payBillForm_nextBtn_nav" data-toggle="tab" href="#invoice-details" data-step="2">02 INVOICE DETAILS</a></li>
                            <li class=""><a class="payBillForm_nextBtn_nav" data-toggle="tab" href="#payment_method-selection" data-step="3">03 PAYMENT SELECTION</a></li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div id="customer-info" data-step="1" class="tab-pane fade in active tab-content-detail">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="payment-info">
                                        <i class="utility-img"></i>
                                        <h3>Utility</h3>
                                        <p class="title-content">Those small expenses matter to your loved ones while they are a click or tap away from you.</p>
                                        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#myModal">Cut-off Period</button>
                                        <p class="title-info">Cut off period refers to the specific time bills ought to be paid on the system. It is a reminder for customers on when to make the outstanding payments for different(utility) services. It is recommended to pay
                                            within this period to avoid an accumulation of outstanding bills.</p>
                                        <p class="title-info">Please refer to the cutoff period to know the payment period of your bills</p>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="customer-form clearfix">
                                        <h4>Select Service Type</h4>
                                        <div class="input-box">
                                            <div class="clearfix">
                                                <h5>What service do you want to pay?</h5>
                                                <select id="serviceTypeId" name="serviceTypeId">
                                                    <option value="">Select Provider</option>
                                                    @foreach ($serviceTypes as $serviceType)
                                                    <option value="{{ $serviceType->id }}" payment_fee_in_percentage="{{ $serviceType->payment_fee_in_percentage }}">{{ $serviceType->service_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <h4>Enter UID or Customer Service Number</h4>
                                        <div class="input-box">
                                            <div class="clearfix">
                                                <h5>Who do you want to pay for?</h5>
                                                <input id="customer_service_number" name="customer_service_number" type="text" placeholder="Type Here">
                                                <span id="uid_lookup_checking" class="hide">Please wait...</span>
                                            </div>
                                        </div>
                                        <h4>Select Service Provider and Service Number</h4>
                                        <div class="input-box">
                                            <div class="clearfix">
                                                <h5>Service Provider:</h5>
                                                <select id="serviceProviderId" name="serviceProviderId">
                                                    <option value="">Select Provider</option>
                                                    {{-- @foreach ($serviceProviders as $serviceProvider)
                                                    <option value="{{ $serviceProvider->id }}" service_id="{{ $serviceProvider->service_id }}">{{ $serviceProvider->provider_name }}</option>
                                                    @endforeach --}}
                                                </select>
                                                <span id="serviceProviderId_loading" class="hide">Please wait...</span>
                                            </div>
                                            <div class="clearfix">
                                                <h5>Service Number:</h5>
                                                <input id="service_provider_service_id" name="service_provider_service_id" type="text" readonly="">
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-default payBillForm_nextBtn">Continue</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="invoice-details" data-step="2" class="tab-pane fade tab-content-detail">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="payment-info">
                                        <i class="utility-img"></i>
                                        <h3>Utility</h3>
                                        <p class="title-content">Those small expenses matter to your loved ones while they are a click or tap away from you.</p>
                                        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#myModal">Cut-off Period</button>
                                        <p class="title-info">Cut off period refers to the specific time bills ought to be paid on the system. It is a reminder for customers on when to make the outstanding payments for different(utility) services. It is recommended to pay
                                            within this period to avoid an accumulation of outstanding bills.</p>
                                        <p class="title-info">Please refer to the cutoff period to know the payment period of your bills</p>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="invoice-form customer-form">
                                        <div class="input-box">
                                            <h5>Service Information</h5>
                                            <div class="invoice-detail">
                                                <p>Name<span id="debtor_name"></span></p>
                                                <p>City<span id="debtor_city"></span></p>
                                                <p>Cut-Of<span id="cut_off_date"></span></p>
                                            </div>
                                            <h5>Balance Due</h5>
                                            <div class="invoice-detail">
                                                <input type="hidden" id="payBillAmount" name="payBillAmount" value="0">
                                                <p>Amount<span id="amountDisplay">$0</span></p>
                                                <p>Payment Fee<span id="payBillPaymentFee">$0</span></p>
                                                <p class="total">Total<span id="payBillAmountTotal">$0</span></p>
                                            </div>
                                            <h5>Debtor Cellphone Number</h5>
                                            <div class="invoice-detail callblk clearfix">
                                                <div class="phblk">
                                                    <select id="debtor_phone_code" name="debtor_phone_code" class="selectbox form-control">
                                                        <option value="{{ config('ethiopay.ETHIOOIA_PHONE_CODE') }}">{{ config('ethiopay.ETHIOOIA_PHONE_CODE') }}</option>
                                                    </select>
                                                    <input id="debtor_phone_number" name="debtor_phone_number" type="text">
                                                    <span id="debtor_phone_number_checking" class="hide">Please wait...</span>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-outline">EMAIL INVOICE</button>
                                        <button type="button" class="btn btn-default payBillForm_nextBtn">Continue</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="payment_method-selection" data-step="3" class="tab-pane fade tab-content-detail">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="payment-info">
                                        <i class="utility-img"></i>
                                        <h3>Utility</h3>
                                        <p class="title-content">Those small expenses matter to your loved ones while they are a click or tap away from you.</p>
                                        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#myModal">Cut-off Period</button>
                                        <p class="title-info">Cut off period refers to the specific time bills ought to be paid on the system. It is a reminder for customers on when to make the outstanding payments for different(utility) services. It is recommended to pay
                                            within this period to avoid an accumulation of outstanding bills.</p>
                                        <p class="title-info">Please refer to the cutoff period to know the payment period of your bills</p>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="payment-select customer-form">
                                        <div id="paymentMethodContainer" class="flex-box">
                                            <input id="paymentMethodId" name="paymentMethodId" type="hidden">
                                            @foreach ($paymentMethods as $paymentMethod)
                                            <div class="singlePaymentMethodSection payment-select-detail" paymentMethodId="{{ $paymentMethod->id }}">
                                                <div class="payment-flex">
                                                    <img src="{{ $paymentMethod->payment_method_icon }}" height="26px">
                                                    @if($paymentMethod->method_type == 'card')
                                                    <h3>{{ $paymentMethod->name_on_card }}</h3>
                                                    <!--<h4>Credit Card</h4>-->
                                                    <h5>XXXX-{{ $paymentMethod->card_number }}</h5>
                                                    @elseif($paymentMethod->method_type == 'paypal')
                                                    <!--<h3>Bank of America</h3>-->
                                                    <h4>{{ $paymentMethod->paypal_email }}</h4>
                                                    <h5>Verified</h5>
                                                    @endif
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        <span id="paymentMethodId-error" class="error help-block hide paymentMethodId-error">Please select payment method.</span>
                                        <a href="{{ url('payment-methods/create') }}" class="btn btn-outline btn-575">ADD PAYMENT METHOD</a>
                                        <button id="payBillForm_submitBtn" name="payBillForm_submitBtn" type="submit" class="btn btn-default payBillForm_nextBtn" data-loading-text="Loading..." >Continue</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</main>

<div class="modal fade billpaymodal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <div class="paybillblk">
                    <div class="paybillrow">
                        <h2>Ethiopian Electric Utility( EEU) Bill</h2>
                        <div class="paymentdesc clearfix">
                            <div class="payment_descleft">
                                <h3>Explanation</h3>
                                <p>If you are under Group 1 and pay your Bill for the month of October, the normal payment period starts on the 26th of October and ends on the 4th of November. The payment period with penality starts on the 5th of November and ends on the 14th of November.</p>
                            </div>
                            <div class="payment_table">
                                <div class="saleblkdtl">
                                    <label for="">Normal Sales period  -  (Eth Cal)</label>
                                    <label for="">Payment with penalty   -  (Eth Cal)</label>
                                </div>
                                <div class="responsive_table">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>BILL GROUP</th>
                                                <th>START DATE</th>
                                                <th>END DATE</th>
                                                <th>START DATE</th>
                                                <th>END DATE</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>G1</td>
                                                <td>26</td>
                                                <td>04</td>
                                                <td>05</td>
                                                <td>14</td>
                                            </tr>
                                            <tr>
                                                <td>G2</td>
                                                <td>01</td>
                                                <td>08</td>
                                                <td>09</td>
                                                <td>20</td>
                                            </tr>
                                            <tr>
                                                <td>G3</td>
                                                <td>06</td>
                                                <td>13</td>
                                                <td>21</td>
                                                <td>24</td>
                                            </tr>
                                            <tr>
                                                <td>G4</td>
                                                <td>11</td>
                                                <td>18</td>
                                                <td>19</td>
                                                <td>30</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="paybillrow">
                        <h2>Ethio-Telecom Bill ( ET) Bill</h2>
                        <div class="paymentdesc clearfix">
                            <div class="payment_descleft">
                                <h3>Explanation</h3>
                                <p>New bill starts on the 26th of the bill period and there is no end date.</p>
                            </div>
                            <div class="payment_table">
                                <div class="saleblkdtl">
                                    <label for="">Normal Sales period  -  (Eth Cal)</label>
                                    <label for="">Payment with penalty   -  (Eth Cal)</label>
                                </div>
                                <div class="responsive_table">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>BILL GROUP</th>
                                                <th>START DATE</th>
                                                <th>END DATE</th>
                                                <th>START DATE</th>
                                                <th>END DATE</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>ALL BILLS</td>
                                                <td>26</td>
                                                <td>N/A</td>
                                                <td>N/A</td>
                                                <td>N/A</td>
                                            </tr> 
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="paybillrow">
                        <h2>Addis Ababa Water &amp; Sewarage Authority(AAWSA) Bill</h2>
                        <div class="paymentdesc clearfix">
                            <div class="payment_descleft">
                                <h3>Explanation</h3>
                                <p>New bill starts on the 26th of the bill period month and ends on the 17th of the next month.</p>
                            </div>
                            <div class="payment_table">
                                <div class="saleblkdtl">
                                    <label for="">Normal Sales period  -  (Eth Cal)</label> 
                                </div>
                                <div class="responsive_table">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>BILL GROUP</th>
                                                <th>START DATE</th>
                                                <th>END DATE</th>
                                                <th>START DATE</th>
                                                <th>END DATE</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>ALL BILLS</td>
                                                <td>26</td>
                                                <td>17</td>
                                                <td>N/A</td>
                                                <td>N/A</td>
                                            </tr> 
                                        </tbody>
                                    </table>
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

@push("scripts")
<script type="text/javascript">
    $('#payBillForm').trigger("reset");
    //var payment_Fee_In_Percentage = Number("{{ $payment_Fee_In_Percentage }}");
    var payment_Fee_In_Percentage = 0;
    var uidLookupData = null;
    var service_type_id = 0;
    var payBillForm_step = 1;
    var clickedStep = 0;
    $('.singlePaymentMethodSection').on('click', function (event) {
        var paymentMethodId = $(this).attr('paymentMethodId');
        $('input#paymentMethodId').val(paymentMethodId);
        $(".singlePaymentMethodSection.active").removeClass('active');
        $(this).addClass('active');
    });

    $('#payBillAmount').on('keyup change keypress', function (event) {
        var payBillAmount = Number($(this).val());
        if (isNaN(payBillAmount)) {
            payBillAmount = 0;
        }
        var payBillPaymentFee = Number(((payBillAmount * payment_Fee_In_Percentage) / 100).toFixed(2));
        var payBillAmountTotal = Number((payBillAmount + payBillPaymentFee).toFixed(2));
        $('#payBillPaymentFee').html('$' + payBillPaymentFee);
        $('#payBillAmountTotal').html('$' + payBillAmountTotal);
    });

    $("#serviceProviderId").select2().on('select2:select', function (e) {
        var service_id = $(this).find(":selected").attr("service_id");
        $('#service_provider_service_id').val(service_id);
        $('#selected_service_provider_name').html($(this).find(":selected").text());
    });

    $("#serviceTypeId").select2().on('select2:select', function (e) {
        service_type_id = $(this).find(":selected").val();
        payment_Fee_In_Percentage = Number($(this).find(":selected").attr('payment_fee_in_percentage'));
        getServiceProviders();
    });

    function getServiceProviders() {
        var listOptionHtml = "<option value=''>Select Provider</option>";
        if (service_type_id) {
            $('#serviceProviderId_loading').removeClass('hide');
            $.ajax({
                url: "{{url('get-service-provider-by-service-type')}}/" + service_type_id,
                type: "GET",
                beforeSend: function () {

                },
                success: function (listRes) {
                    if (listRes.status == true) {
                        $(listRes.serviceProviders).each(function (index, serviceProvider) {
                            listOptionHtml += "<option value='" + serviceProvider.id + "' service_id='" + serviceProvider.service_id + "'>" + serviceProvider.provider_name + "</option>";
                        });
                        $('#serviceProviderId').html(listOptionHtml);
                    } else {
                        $('#serviceProviderId').html(listOptionHtml);
                    }
                    $('#serviceProviderId_loading').addClass('hide');
                },
                error: function (xhr, status, error) {
                    $('#serviceProviderId').html(listOptionHtml);
                    $('#serviceProviderId_loading').addClass('hide');
                }
            });
        } else {
            $('#serviceProviderId').html(listOptionHtml);
        }
    }

    $('.payBillForm_nextBtn, .payBillForm_nextBtn_nav').click(function (event) {
        var payBillForm = $("#payBillForm");
        payBillForm.validate({
            onkeyup: function (element, event) {
                if (element.name !== "customer_service_number") {
                    $.validator.defaults.onfocusout.call(this, element, event);
                }
            },
            rules: {
                serviceTypeId: {
                    required: true,
                    number: true,
                },
                debtor_phone_code: {
                    required: true,
                },
                debtor_phone_number: {
                    required: true,
                    number: true,
                    remote: {
                        url: "{{url('checkDebtorPhoneNumber')}}",
                        type: "GET",
                        dataType: 'json',
                        async: false,
                        beforeSend: function ( ) {
                            $("#debtor_phone_number_checking").removeClass('hide');
                        },
                        data: {
                            debtor_phone_code: function () {
                                return $("#debtor_phone_code").val();
                            },
                            debtor_phone_number: function () {
                                return $("#debtor_phone_number").val();
                            }
                        },
                        error: function (xhr, status, error) {
                            $("#debtor_phone_number_checking").addClass('hide');
                        },
                        dataFilter: function (data) {
                            var data = JSON.parse(data);
                            $("#debtor_phone_number_checking").addClass('hide');
                            if (data.status == 'true') {
                                return '"true"';
                            }
                            return '"' + data.message + '"';
                        }
                    },
                    minlength: 9,
                    maxlength: 13
                },
                customer_service_number: {
                    required: true,
                    remote: {
                        url: "{{url('pay-bill/check-uid-lookup')}}",
                        type: "GET",
                        dataType: 'json',
                        async: false,
                        data: {
                            service_type_id: function () {
                                return service_type_id;
                            },
                        },
                        beforeSend: function ( ) {
                            $("#uid_lookup_checking").removeClass('hide');
                        },
                        error: function (xhr, status, error) {
                            $("#uid_lookup_checking").addClass('hide');
                        },
                        dataFilter: function (checkUidLookupResponse) {
                            checkUidLookupResponse = JSON.parse(checkUidLookupResponse);
                            $("#uid_lookup_checking").addClass('hide');
                            if (checkUidLookupResponse.status == 'true') {
                                uidLookupData = checkUidLookupResponse.record;
                                updateUidLookupDataToView();
                                return '"true"';
                            }
                            uidLookupData = null;
                            updateUidLookupDataToView();
                            return '"' + checkUidLookupResponse.message + '"';
                        }
                    },
                },
                serviceProviderId: {
                    required: true,
                },
                service_provider_service_id: {
                    required: true,
                },
                payBillAmount: {
                    required: true,
                    number: true,
                    min: 1
                },
                paymentMethodId: {
                    required: true,
                }
            },
            messages: {
            },
            errorPlacement: function (error, element) {
                if (element.attr('name') == 'paymentMethodId') {
                    error.insertAfter($('#paymentMethodContainer'));
                } else if (element.attr('name') == 'serviceProviderId') {
                    error.insertAfter($('#serviceProviderId').siblings(".select2"));
                } else if (element.attr('name') == 'serviceTypeId') {
                    error.insertAfter($('#serviceTypeId').siblings(".select2"));
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function (form) {
                if ($("input#paymentMethodId").val() == "") {
                    $("#paymentMethodId-error").removeClass('hide');
                    return false;
                } else {
                    $("#paymentMethodId-error").addClass('hide');
                }
                submitPayBillFormData(form);
            }
        });

        if ($(this).hasClass('payBillForm_nextBtn_nav') == true) {
            clickedStep = Number($(this).data('step'));
            if (payBillForm_step == clickedStep) {
//                event.stopPropagation();
//                event.preventDefault();
                $('[href="#' + clickedStep + '"]').tab('show');
            } else if (clickedStep < payBillForm_step) {
                payBillForm_step = clickedStep;
            } else {
                if (payBillForm.valid() === true) {
                    var payBillFormNextSectionId = $(this).attr('href');
                    $('[href="#' + payBillFormNextSectionId + '"]').tab('show');
                    payBillForm_step = clickedStep;
                } else {
                    event.stopPropagation();
                    event.preventDefault();
                }
            }
        } else {
            var payBillFormNextSectionId = $(this).parents('.tab-pane').next().attr("id");
            if (payBillForm.valid() === true) {
                $('[href="#' + payBillFormNextSectionId + '"]').tab('show');
                clickedStep = $('[href="#' + payBillFormNextSectionId + '"]').data('step');
                payBillForm_step = clickedStep;
            }
        }
    });

    function updateUidLookupDataToView() {
        var payBillAmount = 0;
        if (uidLookupData) {
            $('#debtor_name').html(uidLookupData.debtor_firstname + ' ' + uidLookupData.debtor_lastname);
            $('#debtor_city').html(uidLookupData.debtor_city);
            $('#cut_off_date').html(uidLookupData.cut_off_date_formated);
            $('#payBillAmount').val(uidLookupData.amount);
            payBillAmount = Number(uidLookupData.amount);
        } else {
            $('#debtor_name').html('N/A');
            $('#debtor_city').html('N/A');
            $('#cut_off_date').html('N/A');
            $('#payBillAmount').val(0);
        }

        if (isNaN(payBillAmount)) {
            payBillAmount = 0;
        }
        var payBillPaymentFee = Number(((payBillAmount * payment_Fee_In_Percentage) / 100).toFixed(2));
        var payBillAmountTotal = Number((payBillAmount + payBillPaymentFee).toFixed(2));
        $('#amountDisplay').html('$' + payBillAmount.toFixed(2));
        $('#payBillPaymentFee').html('$' + payBillPaymentFee.toFixed(2));
        $('#payBillAmountTotal').html('$' + payBillAmountTotal.toFixed(2));
    }

    function submitPayBillFormData(form) {
        $('#payBillForm_submitBtn').button('loading');
        var payBillFormSerialize = $("#payBillForm").serialize();
        var uidLookupDataSerialize = $.param(uidLookupData, true);
        var payBillFormSerializeMerge = payBillFormSerialize + '&' + uidLookupDataSerialize;
        var fnToastErrorMsg = 'Something went wrong';
        $.ajax({
            type: "POST",
            url: "{{url('pay-bill')}}",
            dataType: 'json',
            data: payBillFormSerializeMerge,
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            beforeSend: function () {

            },
            success: function (payBillResponse) {
                if (payBillResponse.status == true) {
                    window.location.href = "{{url('pay-bill-success')}}/" + payBillResponse.transaction_id;
                } else {
                    window.location.href = "{{url('pay-bill-failed')}}";
                }
            },
            error: function (xhr, status, error) {
                $('#payBillForm_submitBtn').button('reset')
                fnToastError(fnToastErrorMsg);
            }
        });
    }
</script>
@endpush


