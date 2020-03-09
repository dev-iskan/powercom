<?php

return [
    'payme_billing_service' => [
        'login' => env('PAYME_LOGIN'),
        'kassa_id' => env('PAYME_BILLING_KASSA_ID'),
        'key' => env('PAYME_BILLING_KEY'),
        'test_key' => env('PAYME_BILLING_TEST_KEY')
    ],
    'click_billing' => [
        'key' => env('CLICK_BILLING_SECRET_KEY'),
        'merchant_id' => env('CLICK_BILLING_MERCHANT_ID'),
        'service_id' => env('CLICK_BILLING_SERVICE_ID'),
        'user_id' => env('CLICK_BILLING_MERCHANT_USER_ID'),
    ],
    'telegram' => [
        'url' => env('TELEGRAM_API_URL'),
        'bot_token' => env('TELEGRAM_BOT_TOKEN'),
        'chat_id' => env('TELEGRAM_GROUP_CHAT_ID')
    ]
];
