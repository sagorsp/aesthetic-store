<?php
return [
    'bkash' => [
        'enabled' => env('BKASH_ENABLED', false),
        'sandbox' => env('BKASH_SANDBOX', true),
        'sandbox_url' => env('BKASH_BASE_URL_SANDBOX', 'https://tokenized.sandbox.bka.sh/v1.2.0-beta/tokenized/checkout'),
        'live_url' => env('BKASH_BASE_URL_LIVE', 'https://tokenized.pay.bka.sh/v1.2.0-beta/tokenized/checkout'),
        'app_key' => env('BKASH_APP_KEY'),
        'app_secret' => env('BKASH_APP_SECRET'),
        'username' => env('BKASH_USERNAME'),
        'password' => env('BKASH_PASSWORD'),
        'callback_url' => env('BKASH_CALLBACK_URL'),
    ],
    'sms'=>[
        'enabled'=>env('SMS_ENABLED',false), 'url'=>env('SMS_API_URL'), 'api_key'=>env('SMS_API_KEY'), 'sender_id'=>env('SMS_SENDER_ID','AestheticStore'),
    ],
    'pathao' => [
        'base_url' => env('PATHAO_BASE_URL', 'https://courier-api-sandbox.pathao.com'),
        'client_id' => env('PATHAO_CLIENT_ID'),
        'client_secret' => env('PATHAO_CLIENT_SECRET'),
        'username' => env('PATHAO_USERNAME'),
        'password' => env('PATHAO_PASSWORD'),
        'grant_type' => env('PATHAO_GRANT_TYPE', 'password'),
        'store_id' => env('PATHAO_STORE_ID'),
    ],
];
