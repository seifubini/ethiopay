<?php

return [
    'rules' => [
        'user' => [
            'create' => [
                'firstname' => 'required',
                'lastname' => 'required',
                'email' => 'required|email|unique:users,email,NULL,NULL,deleted_at,NULL',
                'password' => 'required|min:8|regex:/^(?=.*[0-9])(?=.*[A-Z]).+$/',
                'phone_code' => 'required',
                'phone_number' => 'required|integer',
//                'ethiopia_phone_code' => '',
                //'ethiopia_phone_number' => 'integer',
                'profile_picture' => 'file|mimes:jpeg,jpg,png',
                'federal_tax_id' => '',
                'country_id' => 'required|integer',
                'state_id' => 'required|integer',
                'city_id' => 'required|integer',
                'address_line_1' => 'required',
                'zipcode' => 'required',
            ],
            'profileUpdate' => [
                'firstname' => 'required',
                'lastname' => 'required',
                'phone_code' => 'required',
                'phone_number' => 'required|integer',
                'ethiopia_phone_code' => 'required|',
                'ethiopia_phone_number' => 'required|integer',
                'profile_picture' => 'file|mimes:jpeg,jpg,png',
                'federal_tax_id' => '',
                'country_id' => 'required|integer',
                'state_id' => 'required|integer',
                'city_id' => 'required|integer',
                'address_line_1' => 'required',
                'zipcode' => 'required',
            ],
        ],
        'setting' => [
            'update' => [
                // 'key' => 'required',
                'value' => 'required|numeric|Regex:/^[a-zA-Z0-9.\s]+$/',
            ]
        ],
        'payBillStore' => [
            'serviceTypeId' => 'required|integer|min:1',
            'serviceProviderId' => 'required|integer|min:1',
//            'payBillAmount' => 'required|numeric|min:1',
            'paymentMethodId' => 'required|integer|min:1',
            'uid_lookup_id' => 'required|integer|min:1',
        ],
        'paymentMethodStore' => [
            'paymentMethodType' => 'required|in:card,paypal',
            'stripe_token' => 'required_if:paymentMethodType,card'
        ],
        'contact' => [
            'name' => 'required',
            'email' => 'required|email',
            'phone_code' => 'required',
            'phone_number' => 'required|integer',
            'subject' => 'required',
            'message' => 'required',
        ]

    ],
    'messages' => [
        'user' => [
            'create' => [
            ],
            'update' => [
            ],
        ],
    ],
];
