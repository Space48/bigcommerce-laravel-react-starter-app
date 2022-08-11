<?php

return [

    // Used to authenticate API requests
    'client_id' => env('BIGCOMMERCE_CLIENT_ID') ?? '',
    'client_secret' => env('BIGCOMMERCE_CLIENT_SECRET') ?? '',

    // Used to verify webhook messages from BC
    'webhook_secret' => env('BIGCOMMERCE_WEBHOOK_SECRET') ?? '',

    // Used to link back to the app within a store
    'app_id' => env('BIGCOMMERCE_APP_ID') ?? '',
];
