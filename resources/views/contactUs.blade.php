@extends('layouts.beforeLogin1')
@section("title", "Contact Us")

@section('content')
<main class="padding-none contact_formblk">
    <div class="container">
        <div class="termsblk clearfix">
            <div class="col-md-5 cntdtl">  
                <i class="cnticon"></i>      
                <h2>Contact Us</h2>
                <p class="title-content">Ethiopay welcomes your questions or comments regarding the Terms: </p>
                <address>Ethiopian Financial Systems LLC <br>
                    1100 Peachtree St. NW Suite 200, <br>
                    Atlanta, Georgia 30309
                </address>
                <div class="mailblk">
                    <span>Email Address: </span>
                    <a href="mailto:info@ethiopianfinancialsystems.com">info@ethiopianfinancialsystems.com</a>
                </div>
                <div class="telephoneblk">
                    <span>Telephone number: </span>
                    <span>1-877-797-0885</span>
                </div>
                <!-- <p>Effective as of February 28, 2018</p> -->
            </div>
            <div class="col-md-7 ">
                <h4>Send us a message</h4>
                <form class="editproform" id="contactForm">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="name">Your Name</label>
                        <input type="text" name="name" placeholder="Type here">
                    </div>
                    <div class="form-group">
                        <label for="email">Your Email</label>
                        <input type="text" name="email" placeholder="Type here">
                    </div>
                    <div class="form-group clearfix">
                        <label>Phone number</label>
                        <div class="phblk">
                            <div class="contact_form_phncode">
                                <select id="phone_code" name="phone_code" class="selectbox form-control">
                                    <option value="">Code</option>
                                    <option value="{{ $phone_code_united->phone_code }}" country_code="{{ $phone_code_united->sortname }}" >{{ $phone_code_united->name . ' (' . $phone_code_united->phone_code .')' }}</option>
                                    @foreach($phone_codes as $phone_code)
                                    <option value="{{ $phone_code->phone_code }}" country_code="{{ $phone_code->sortname }}" >{{ $phone_code->name . ' (' . $phone_code->phone_code .')' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="contact_form_phnno">
                                <input type="text" name="phone_number" id="phone_number" placeholder="Type here">
                                <span id="phone_number_checking" class="hide">Please wait...</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <input type="text" name="subject" placeholder="Type here">
                    </div>
                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea placeholder="Type here" name="message"></textarea>
                    </div>
                    <button class="btn btn-default" type="submit" id="submitBtnContactForm">Continue</button>
                </form>
            </div>
        </div>
    </div>     
</main>
@endsection

@push("scripts")
<script type="text/javascript">
    $("#contactForm").validate({
        ignore: [],
        rules: {
            name: {
                required: true,
            },
            email: {
                required: true,
                email: true,
            },
            phone_code: {
                required: true,
            },
            phone_number: {
                required: true,
                number: true,
                remote: {
                    url: "{{url('validateContactUsPhoneNumber')}}",
                    type: "GET",
                    dataType: 'json',
                    async: false,
                    beforeSend: function ( ) {
                        $("#phone_number_checking").removeClass('hide');
                    },
                    data: {
                        phone_code: function () {
                            return $("#phone_code").val();
                        },
                        phone_number: function () {
                            return $("#phone_number").val();
                        }
                    },
                    error: function (xhr, status, error) {
                        $("#phone_number_checking").addClass('hide');
                    },
                    dataFilter: function (data) {
                        var data = JSON.parse(data);
                        $("#phone_number_checking").addClass('hide');
                        if (data.status == 'true') {
                            return '"true"';
                        }
                        return '"' + data.message + '"';
                    }
                },
                minlength: 9,
                maxlength: 13
            },
            subject: {
                required: true,
            },
            message: {
                required: true,
            },
        }, messages: {
        }, errorPlacement: function (error, element) {
            //error.insertAfter(element.parent());
            error.insertAfter(element);
        }, highlight: function (element) {

        }, unhighlight: function (element) {

        },
        errorElement: 'span',
        errorClass: 'input_error',
        submitHandler: function (form) {
            submitContactUsForm(form);
            $('#submitBtnContactForm').button('loading');
        },
    });

    function submitContactUsForm(form) {
        var fnToastErrorMsg = 'Something went wrong';
        $.ajax({
            type: "POST",
            url: "{{ url('contact') }}",
            dataType: 'json',
            data: $("#contactForm").serialize(),
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            success: function (data) {
                if (data.status == true) {
                    $('#contactForm').trigger("reset");
                    $('#submitBtnContactForm').button("reset");
                    fnToastSuccess(data.message);

                }
            },
            error: function (xhr, status, error) {
                console.log(xhr);
                $('#submitBtnContactForm').button("reset");
                fnToastError(fnToastErrorMsg);
            }
        });
    }
</script>
@endpush

