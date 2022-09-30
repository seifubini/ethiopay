
@extends('admin.layout.admin')

@push('styles')
@endpush
@section('title',"Edit User")
@section('content')

@section('pageHeading')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-4">
        <h2>@yield('title')</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{url('admin/dashboard')}}">Dashboard</a>
            </li>
            <li>
                <a href="{{url('admin/users')}}">{{ isset($module_name) ? $module_name : '' }}</a>
            </li>
            <li class="active">
                @yield('title')
            </li>
        </ol>
    </div>
    <div class="col-sm-8">

    </div>
</div>

@endsection
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5> @yield('title')</h5>
                </div>
                <div class="ibox-content">

                    {!! Form::model($user,['url' => 'admin/users/'.$user->id,'method' => 'PUT','enctype'=>'multipart/form-data','class'=>'form-horizontal','id'=>'user']) !!}
                    @include("admin.user._form")
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')

<script>
    $(document).ready(function () {
        var user_id = "{{isset($user->id)?$user->id:0}}";
        jQuery.validator.addMethod("validation_rule_password", function (value, element) {
            var regExp = /^(?=.*[0-9])(?=.*[A-Z]).+$/;
            return this.optional(element) || regExp.test(value);
        }, "Password must contain at least one number and one uppercase letter.");
        
        $("#user").validate({
            ignore: [],
            errorElement: 'div',
            errorClass: 'text-danger text-left text-bold',
            rules: {
                firstname: {
                    required: true,
                },
                lastname: {
                    required: true,
                },
                email: {
                    required: true,
                    remote: {
                        url: "{{url('admin/users/checkUniqueEmail')}}",
                        type: "GET",
                        dataType: 'json',
                        async: false,
                        beforeSend: function () {
                            $("#email_checking").removeClass('hide');
                        },
                        data: {
                            user_id: function () {
                                return user_id;
                            },
                            email: function () {
                                return $("#email").val();
                            }
                        },
                        error: function (xhr, status, error) {
                            $("#email_checking").addClass('hide');
                        },
                        dataFilter: function (data) {
                            var data = JSON.parse(data);
                            $("#email_checking").addClass('hide');
                            if (data.status == 'true') {
                                return '"true"';
                            }
                            return '"' + data.message + '"';
                        }
                    }
                },
                password: {
                    minlength: 8,
                    validation_rule_password: true,
                },
                phone_code: {
                    required: true,
                },
                phone_number: {
                    required: true,
                    number: true,
                    remote: {
                        url: "{{url('admin/users/checkUniquePhoneNumber')}}",
                        type: "GET",
                        dataType: 'json',
                        async: false,
                        beforeSend: function () {
                            $("#phone_number_checking").removeClass('hide');
                        },
                        data: {
                            user_id: function () {
                                return user_id;
                            },
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
                    required: true,
                },
                ethiopia_phone_number: {
                    required: true,
                    number: true,
                    remote: {
                        url: "{{url('admin/users/checkUniqueEthiopiaPhoneNumber')}}",
                        type: "GET",
                        dataType: 'json',
                        async: false,
                        beforeSend: function () {
                            $("#ethiopia_phone_number_checking").removeClass('hide');
                        },
                        data: {
                            user_id: function () {
                                return user_id;
                            },
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
                    extension: "jpg|jpeg|png",
                },
                federal_tax_id: {
                    required: true,
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
                firstname: {
                    required: "First name is required.",
                },
                lastname: {
                    required: "Last name is required.",
                },
                email: {
                    required: "Email is required.",
                    remote: "Email is already registered.",
                },
                phone_code: {
                    required: "Phone code is required.",
                },
                phone_number: {
                    required: "Phone number is required.",
                },
                ethiopia_phone_code: {
                    required: "Ethiopia phone code is required.",
                },
                ethiopia_phone_number: {
                    required: "Ethiopia phone number is required.",
                },
                profile_picture: {
                    extension: "Please upload valid jpg|jpeg|png file.",
                },
                federal_tax_id: {
                    required: "Federal tax id is required.",
                },
                country_id: {
                    required: "Country is required.",
                },
                state_id: {
                    required: "State is required.",
                },
                city_id: {
                    required: "City is required.",
                },
                address_line_1: {
                    required: "Address is required.",
                },
                zipcode: {
                    required: "Zipcode is required.",
                },
            },
            highlight: function (element) {
                $(element).closest('.form-control').addClass('has-error');
            },
            unhighlight: function (element) {
                $(element).closest('.form-control').removeClass('has-error');
            },
            errorPlacement: function (error, element) {
                if (element.hasClass('select2-hidden-accessible'))
                    error.insertAfter(element.next('span'));
                else
                    error.insertAfter(element);
            },
            submitHandler: function (form) {
                $('.submit-button').button('loading');
                if ($('#user').valid()) {
                    form.submit();
                } else {
                    $('.submit-button').button('reset');
                }
            },
        });
    });
    
</script>
@endpush

