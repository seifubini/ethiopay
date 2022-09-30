@extends('layouts.beforeLogin2')
@section("title", "Register")

@section('content')
<main class="signup-page">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="eth-card">
                    <div class="welcome-text">
                        <h2>Welcome to Ethiopay</h2>
                        <p>Create your account by filling the form bellow.</p>
                    </div>
                    <form id="registerForm" name="registerForm" class="form-horizontal" method="POST" action="javascript:void(0)">
                        {{ csrf_field() }}
                        <div class="eth-form">
                            <div class="form-group field">
                                <input id="firstname" name="firstname" type="text">
                                <label for="firstname">First Name</label>
                            </div>
                            <div class="form-group field">
                                <input id="lastname" name="lastname" type="text">
                                <label for="lastname">Last Name</label>
                            </div>
                            <div class="form-group field">
                                <input id="email" name="email" type="text">
                                <label for="email">Email</label>
                            </div>
                            <div class="form-group field paswd-field">
                                <input id="password" name="password" type="password">
                                <label for="password">Password</label>
                                <i class="hideShowPassword "></i>
                                <!-- <img class="hideShowPassword" src="{{ asset('img/fa-eye.svg') }}"> -->
                            </div>
                            <div class="form-group field no-focus">
                                <select id="phone_code" name="phone_code" class="selectbox form-control">
                                    <option value="">Code</option>
                                    <option value="{{ $phone_code_united->phone_code }}" country_code="{{ $phone_code_united->sortname }}" >{{ $phone_code_united->name . ' (' . $phone_code_united->phone_code .')' }}</option>
                                    @foreach($phone_codes as $phone_code)
                                    <option value="{{ $phone_code->phone_code }}" country_code="{{ $phone_code->sortname }}" >{{ $phone_code->name . ' (' . $phone_code->phone_code .')' }}</option>
                                    @endforeach
                                </select>
                                <label for="phone_code">Phone Code</label>
                            </div>
                            <div class="form-group field">
                                <input id="phone_number" name="phone_number" type="text">
                                <label for="phone_number">Phone Number</label>
                                <span id="phone_number_checking" class="hide">Please wait...</span>
                            </div>
<!--                            <div class="form-group field no-focus">
                                <input id="ethiopia_phone_code" name="ethiopia_phone_code" type="text" value="{{ config('ethiopay.ETHIOOIA_PHONE_CODE') }}" readonly="">
                                <label for="ethiopia_phone_code">Ethiopia Phone Code</label>
                            </div>
                            <div class="form-group field">
                                <input id="ethiopia_phone_number" name="ethiopia_phone_number" type="text">
                                <label for="ethiopia_phone_number">Ethiopia Phone Number</label>
                                <span id="ethiopia_phone_number_checking" class="hide">Please wait...</span>
                            </div>-->
<!--                            <div class="form-group field no-focus">
                                <input id="profile_picture" name="profile_picture" type="file">
                                <label for="profile_picture">Profile Picture</label>
                            </div>-->
                            {{--  <div class="form-group field">  --}}
                                {{--  <input id="federal_tax_id" name="federal_tax_id" type="text">  --}}
                                {{--  <label for="federal_tax_id">Federal Tax ID</label>  --}}
                            {{--  </div>  --}}
                            <div class="form-group field no-focus">
                                <select id="country_id" name="country_id" class="selectbox form-control">
                                    <option value="">Country</option>
                                    @foreach($countries as $country)
                                    <option value="{{ $country->id }}" >{{ $country->name }}</option>
                                    @endforeach
                                </select>
                                <label for="country_id">Country</label>
                            </div>
                            <div class="form-group field no-focus">
                                <select id="state_id" name="state_id" class="selectbox form-control">
                                    <option value=''>State</option>
                                </select>
                                <label for="state_id">State</label>
                                <span id="state_id_fetching" class="hide">Please wait...</span>
                            </div>
                            <div class="form-group field no-focus">
                                <select id="city_id" name="city_id" class="selectbox form-control">
                                    <option value=''>City</option>
                                </select>
                                <label for="city_id">City</label>
                                <span id="city_id_fetching" class="hide">Please wait...</span>
                            </div>
                            <div class="form-group field">
                                <input id="address_line_1" name="address_line_1" type="text">
                                <label for="address_line_1">Address</label>
                            </div>
                            <div class="form-group field">
                                <input id="zipcode" name="zipcode" type="text">
                                <label for="zipcode">Zipcode</label>
                            </div>
                        </div>
                        <button type="submit" id="submitBtnRegisterForm" name="submitBtnRegisterForm" class="btn btn-primary" data-loading-text="Loading...">Sign Up</button>
                    </form>
                </div>
            </div>
            <div class="col-md-8">
                <div class="eth-card signup-card">
                    <div class="logo">
                        <img src="{{ asset('img/logo-white-text.png') }}">
                    </div>
                    <h1>Do you already have an account?</h1>
                    <p>That’s awesome! You can login by clicking on the button below. To skip this next time, you can ask
                        us to remember your login credentials.</p>
                    <a href="{{ route('login') }}" class="btn btn-default login-btn">Sign In</a>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push("scripts")
<script type="text/javascript">
    var country_id = 0;
    var state_id = 0;

    jQuery.validator.addMethod("validation_rule_password", function (value, element) {
        //var regExp = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;
        //var regExp = /^(?=.*[0-9])(?=.*[a-zA-Z]).+$/;
        var regExp = /^(?=.*[0-9])(?=.*[A-Z]).+$/;
        return this.optional(element) || regExp.test(value);
    }, "Password must contain at least one number and one uppercase letter.");

    $.validator.addMethod("oneNumberOneUppercase", function (value, element) {
        return this.optional(element) || /[0-9]+[A-Z]+/.test(value);
    }, "Password must contain at least one number and one uppercase letter");

    $("#registerForm").validate({
        ignore: [],
        rules: {
            firstname: {
                required: true,
            },
            lastname: {
                required: true,
            },
            email: {
                required: true,
                email: true,
                remote: "{{url('checkEmailUnique')}}",
            },
            password: {
                required: true,
                minlength: 8,
                validation_rule_password: true,
                {{--  atLeastOneUppercaseLetter: true,  --}}
            },
            phone_code: {
                required: true,
            },
            phone_number: {
                required: true,
                number: true,
                remote: {
                    url: "{{url('checkPhoneNumberUnique')}}",
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
            ethiopia_phone_code: {
//                required: true,
            },
            ethiopia_phone_number: {
//                required: true,
                number: true,
                remote: {
                    url: "{{url('checkEthiopiaPhoneNumberUnique')}}",
                    type: "GET",
                    dataType: 'json',
                    async: false,
                    beforeSend: function ( ) {
                        $("#ethiopia_phone_number_checking").removeClass('hide');
                    },
                    data: {
                        ethiopia_phone_code: function () {
                            return $("#ethiopia_phone_code").val();
                        },
                        ethiopia_phone_number: function () {
                            return $("#ethiopia_phone_number").val();
                        }
                    },
                    error: function (xhr, status, error) {
                        $("#ethiopia_phone_number_checking").addClass('hide');
                    },
                    dataFilter: function (data) {
                        var data = JSON.parse(data);
                        $("#ethiopia_phone_number_checking").addClass('hide');
                        if (data.status == 'true') {
                            return '"true"';
                        }
                        return '"' + data.message + '"';
                    }
                },
                minlength: 9,
                maxlength: 13
            },
            profile_picture: {
//                required: true,
                extension: "jpg|jpeg|png",
            },
            federal_tax_id: {
//                required: true,
            },
            country_id: {
                required: true,
            },
            state_id: {
                required: true,
            },
            city_id: {
                required: true,
            },
            address_line_1: {
                required: true,
            },
            zipcode: {
                required: true,
            },
        }, messages: {
            email: {
                remote: "Email is already registered.",
            },
            profile_picture: {
                extension: "Please upload valid jpg|jpeg|png file.",
            },
        },
        errorPlacement: function (error, element) {
            //error.insertAfter(element.parent());
            error.insertAfter(element);
        },
        highlight: function (element) {

        },
        unhighlight: function (element) {

        },
        errorElement: 'span',
        errorClass: 'input_error',
        submitHandler: function (form) {
            $('#submitBtnRegisterForm').prop('disabled', 'disabled');
            if ($('#registerForm').valid()) {
                submitRegistrationFormData(form);
            } else {
                $('#submitBtnRegisterForm').prop('disabled', false);
            }
        },
    });

    function submitRegistrationFormData(form) {
        $('#submitBtnRegisterForm').button('loading');
        var formData = new FormData(form);
        var fnToastErrorMsg = 'Something went wrong';

        $.ajax({
            type: "POST",
            url: "{{url('register')}}",
            dataType: 'json',
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            beforeSend: function () {

            },
            success: function (data) {
                if (data.status == true) {
                    window.location.href = "{{url('login')}}";
                } else {
                    $('#submitBtnRegisterForm').button('reset');
                    fnToastError(data.message);
                }
            },
            error: function (xhr, status, error) {
                $('#submitBtnRegisterForm').button('reset')
                fnToastError(fnToastErrorMsg);
            }
        });
    }

    $('#registerForm').on('change', '#country_id', function (event) {
        country_id = $(this).val();
        getStateList();
    });
    function getStateList() {
        state_id = 0;
        getCityList();
        var stateListOptionHtml = "<option value=''>State</option>";
        if (country_id) {
            $('#state_id_fetching').removeClass('hide');
            $.ajax({
                url: "{{url('state/getStatesByCountry')}}/" + country_id,
                type: "GET",
                beforeSend: function () {

                },
                success: function (stateListRes) {
                    if (stateListRes.status == true) {
                        $(stateListRes.states).each(function (index, state) {
                            stateListOptionHtml += "<option value='" + state.id + "'>" + state.name + "</option>";
                        });
                        $('#registerForm #state_id').html(stateListOptionHtml);
                    } else {
                        $('#registerForm #state_id').html(stateListOptionHtml);
                    }
                    $('#state_id_fetching').addClass('hide');
                },
                error: function (xhr, status, error) {
                    $('#registerForm #state_id').html(stateListOptionHtml);
                    $('#state_id_fetching').addClass('hide');
                }
            });
        } else {
            $('#registerForm #state_id').html(stateListOptionHtml);
        }
    }

    $('#registerForm').on('change', '#state_id', function (event) {
        state_id = $(this).val();
        getCityList();
    });
    function getCityList() {
        var cityListOptionHtml = "<option value=''>City</option>";
        if (state_id) {
            $('#city_id_fetching').removeClass('hide');
            $.ajax({
                url: "{{url('city/getCitiesByState')}}/" + state_id,
                type: "GET",
                beforeSend: function () {

                },
                success: function (cityListRes) {
                    if (cityListRes.status == true) {
                        $(cityListRes.cities).each(function (index, city) {
                            cityListOptionHtml += "<option value='" + city.id + "'>" + city.name + "</option>";
                        });
                        $('#registerForm #city_id').html(cityListOptionHtml);
                    } else {
                        $('#registerForm #city_id').html(cityListOptionHtml);
                    }
                    $('#city_id_fetching').addClass('hide');
                },
                error: function (xhr, status, error) {
                    $('#registerForm #city_id').html(cityListOptionHtml);
                    $('#city_id_fetching').addClass('hide');
                }
            });
        } else {
            $('#registerForm #city_id').html(cityListOptionHtml);
        }
    }
</script>
@endpush

