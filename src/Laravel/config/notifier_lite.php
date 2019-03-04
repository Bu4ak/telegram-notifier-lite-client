<?php

return [
    'token' => [
        'default' => env('TELEGRAM_NOTIFIER_LITE_TOKEN', 'PUT YOUR TOKEN THERE'),
        //add your other tokens
    ],
    'api_base_url' => env('TELEGRAM_NOTIFIER_LITE_BASE_URL', 'http://localhost:3000/'),
];
