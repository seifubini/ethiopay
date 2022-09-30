<?php

return [
    'rules' => [
        'AdminUserMessage' => [
            'create' => [
                'message' => 'required',
                'user_id' => 'required|integer'
            ],
        ],
    ],
    'messages' => [
        'AdminUserMessage' => [
            'create' => [
                
            ],
        ],
    ],
];
