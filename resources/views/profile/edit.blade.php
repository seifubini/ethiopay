@extends('layouts.app')
@section("title", "Profile Update")

@section('content')
<main class="ep-tabular-format">
    <section>
        <div class="container">
            <div class="tabular-main">
                <div class="tab-content">
                    <div class="tab-pane active fade in tab-content-detail">
                        <div class="row">
                            <div class="col-md-12">
                                <form id="profileForm" name="profileForm" class="editproform" method="POST" action="javascript:void(0)">
                                    <div class="form-group clearfix">
                                        <div class="width50">
                                            <label for="firstname">FIRST NAME</label>
                                            <input id="firstname" name="firstname" type="text" value="{{ $user->firstname }}">
                                        </div>
                                        <div class="width50">
                                            <label for="lastname">LAST NAME</label>
                                            <input id="lastname" name="lastname" type="text" value="{{ $user->lastname }}">
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
                                        <div class="width50">
                                            <label for="email">EMAIL</label>
                                            <input name="email" type="email" value="{{ $user->email }}" disabled="disabled">
                                        </div>
                                        <div class="width50 field paswd-field">
                                            <label for="password">PASSWORD</label>
                                            <input id="password" name="password" type="password">
                                            <i class="hideShowPassword "></i>
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
                                        <div class="width50 phblk">
                                            <label for="phone_number">PHONE NUMBER</label>
                                            <select id="phone_code" name="phone_code" class="selectbox form-control">
                                                <option value="">Code</option>                                                
                                                <option value="{{ $phone_code_united->phone_code }}" {{ ($phone_code_united->phone_code == $user->phone_code ? 'selected="selected"' : '') }} country_code="{{ $phone_code_united->sortname }}" >{{ $phone_code_united->name . ' (' . $phone_code_united->phone_code .')' }}</option>                                                
                                                @foreach($phone_codes as $phone_code)
                                                <option value="{{ $phone_code->phone_code }}" {{ ($phone_code->phone_code == $user->phone_code ? 'selected="selected"' : '') }} country_code="{{ $phone_code->sortname }}" >{{ $phone_code->name . ' (' . $phone_code->phone_code.')' }}</option>
                                                @endforeach
                                            </select>
                                            <input id="phone_number" name="phone_number" type="text" value="{{ $user->phone_number }}">
                                            <span id="phone_number_checking" class="hide">Please wait...</span>
                                        </div>
                                        <div class="width50 phblk">
                                            <label for="ethiopia_phone_number">ETHIOPIA PHONE NUMBER</label>
                                            <select id="ethiopia_phone_code" name="ethiopia_phone_code" class="selectbox form-control">
                                                <option value="">Code</option>
                                                <option value="{{ $phone_code_united->phone_code }}" {{ ($phone_code_united->phone_code == $user->ethiopia_phone_code ? 'selected="selected"' : '') }} country_code="{{ $phone_code_united->sortname }}" >{{ $phone_code_united->name . ' (' . $phone_code_united->phone_code .')' }}</option>                                                
                                                @foreach($phone_codes as $phone_code)
                                                <option value="{{ $phone_code->phone_code }}" {{ ($phone_code->phone_code == $user->ethiopia_phone_code ? 'selected="selected"' : '') }} country_code="{{ $phone_code->sortname }}" >{{ $phone_code->name . ' (' . $phone_code->phone_code.')' }}</option>
                                                @endforeach
                                            </select>
                                            <input id="ethiopia_phone_number" name="ethiopia_phone_number" type="text" value="{{ $user->ethiopia_phone_number }}">
                                            <span id="ethiopia_phone_number_checking" class="hide">Please wait...</span>
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
                                        <div class="width50">
                                            <label for="profile_picture">PROFILE PICTURE</label>
                                            <input id="profile_picture" name="profile_picture" type="file">
                                        </div>
                                        <div class="width50">
                                            <label for="federal_tax_id">FEDERAL TAX ID</label>
                                            <input id="federal_tax_id" name="federal_tax_id" type="text" value="{{ $user->federal_tax_id }}">
                                        </div>
                                    </div>                                    
                                    <div class="form-group clearfix">
                                        <div class="width50">
                                            <label for="country_id">Country</label>
                                            <select id="country_id" name="country_id" class="selectbox form-control">
                                                <option value="">Country</option>
                                                @foreach($countries as $country)
                                                <option value="{{ $country->id }}" {{ ($country->id == $user->addressData->country_id ? 'selected="selected"' : '') }} >{{ $country->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="width50">
                                            <label for="state_id">State</label>
                                            <select id="state_id" name="state_id" class="selectbox form-control">
                                                <option value=''>State</option>
                                                @foreach($states as $state)
                                                <option value="{{ $state->id }}" {{ ($state->id == $user->addressData->state_id ? 'selected="selected"' : '') }} >{{ $state->name }}</option>
                                                @endforeach
                                            </select>
                                            <span id="state_id_fetching" class="hide">Please wait...</span>
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
                                        <div class="width50">
                                            <label for="city_id">City</label>
                                            <select id="city_id" name="city_id" class="selectbox form-control">
                                                <option value=''>City</option>
                                                @foreach($cities as $city)
                                                <option value="{{ $city->id }}" {{ ($city->id == $user->addressData->city_id ? 'selected="selected"' : '') }} >{{ $city->name }}</option>
                                                @endforeach
                                            </select>
                                            <span id="city_id_fetching" class="hide">Please wait...</span>
                                        </div>
                                        <div class="width50">
                                            <label for="address_line_1">Address</label>
                                            <input id="address_line_1" name="address_line_1" type="text" value="{{ $user->addressData->address_line_1 }}">
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
                                        <div class="width50">
                                            <label for="zipcode">Zipcode</label>
                                            <input id="zipcode" name="zipcode" type="text" value="{{ $user->addressData->zipcode }}">
                                        </div>
                                        <div class="width50">
                                            <label></label>
                                        </div>
                                    </div>
                                    <!--                                    <div class="form-group clearfix">
                                                                            <div class="width50">
                                                                                <label></label>
                                                                            </div>
                                                                            <div class="width50">
                                                                                <label></label>
                                                                            </div>
                                                                        </div>-->
                                    <button type="submit" id="submitBtnProfileForm" name="submitBtnProfileForm" class="btn btn-default" data-loading-text="Loading...">Save</button>
                                </form>
                            </div>
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
    var country_id = 0;
    var state_id = 0;

    jQuery.validator.addMethod("validation_rule_password", function (value, element) {
        //var regExp = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;
        var regExp = /^(?=.*[0-9])(?=.*[a-zA-Z])([a-zA-Z0-9]+)$/;
        return this.optional(element) || regExp.test(value);
    }, "Password must contain at least 1 alpha, 1 number and minimum 8 character.");

    $("#profileForm").validate({
        ignore: [],
        rules: {
            firstname: {
                required: true,
            },
            lastname: {
                required: true,
            },
            password: {
                minlength: 8,
                validation_rule_password: false
            },
            phone_code: {
                required: true,
            },
            phone_number: {
                required: true,
                number: true,
                remote: {
                    url: "{{url('checkPhoneNumberUniqueProfileEdit')}}",
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
                    url: "{{url('checkEthiopiaPhoneNumberUniqueProfileEdit')}}",
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
            $('#submitBtnProfileForm').prop('disabled', 'disabled');
            if ($('#profileForm').valid()) {
                submitRegistrationFormData(form);
            } else {
                $('#submitBtnProfileForm').prop('disabled', false);
            }
        },
    });

    function submitRegistrationFormData(form) {
        $('#submitBtnProfileForm').button('loading');
        var formData = new FormData(form);
        var fnToastErrorMsg = 'Something went wrong';

        $.ajax({
            type: "POST",
            url: "{{url('profile')}}",
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
//                    fnToastSuccess(data.message);
//                    $('#headerSectionProfilePicture').attr('src', data.user.profile_picture_small);
//                    $("#profile_picture").val(null);
//                    $("#password").val('');
                    window.location.href = "{{url('profile')}}";
                } else {
                    $('#submitBtnProfileForm').button('reset');
                    fnToastError(data.message);
                }
            },
            error: function (xhr, status, error) {
                $('#submitBtnProfileForm').button('reset')
                fnToastError(fnToastErrorMsg);
            }
        });
    }

    $('#profileForm').on('change', '#country_id', function (event) {
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
                        $('#profileForm #state_id').html(stateListOptionHtml);
                    } else {
                        $('#profileForm #state_id').html(stateListOptionHtml);
                    }
                    $('#state_id_fetching').addClass('hide');
                },
                error: function (xhr, status, error) {
                    $('#profileForm #state_id').html(stateListOptionHtml);
                    $('#state_id_fetching').addClass('hide');
                }
            });
        } else {
            $('#profileForm #state_id').html(stateListOptionHtml);
        }
    }

    $('#profileForm').on('change', '#state_id', function (event) {
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
                        $('#profileForm #city_id').html(cityListOptionHtml);
                    } else {
                        $('#profileForm #city_id').html(cityListOptionHtml);
                    }
                    $('#city_id_fetching').addClass('hide');
                },
                error: function (xhr, status, error) {
                    $('#profileForm #city_id').html(cityListOptionHtml);
                    $('#city_id_fetching').addClass('hide');
                }
            });
        } else {
            $('#profileForm #city_id').html(cityListOptionHtml);
        }
    }
</script>
@endpush