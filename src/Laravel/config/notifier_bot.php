<?php

return [
    'token' => [
        'default' => env('TELEGRAM_NOTIFIER_TOKEN', 'PUT YOUR TOKEN THERE'),
        //add your other tokens
    ],
    'api_endpoint' => env('TELEGRAM_NOTIFIER_ENDPOINT', 'http://localhost:3000/message'),
];
