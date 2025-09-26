<?php

// config for Abather/TawakkalnaMessage
return [
    'base_url' => env('TAWAKKALNA_BASE_URL', 'https://external.api.tawakkalna.nic.gov.sa/messaging/v1'),
    // Authorization Method bearer or basic
    'authorization_method' => env('TAWAKKALNA_AUTHORIZATION_METHOD', 'bearer'),
    // Required when authorization method is bearer
    'token' => env('TAWAKKALNA_TOKEN'),
    // Required when authorization method is basic
    'username' => env('TAWAKKALNA_USERNAME'),
    'password' => env('TAWAKKALNA_PASSWORD'),

    'identifier_attribute' => env('TAWAKKALNA_IDENTIFIER_ATTRIBUTE', 'national_id'),
];
