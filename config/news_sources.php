<?php

return [
    'newsapi' => [
        'api_key' => env('NEWSAPI_KEY'),
        'enabled' => true,
    ],
    'guardian' => [
        'api_key' => env('GUARDIAN_API_KEY'),
        'enabled' => true,
    ],
    'nytimes' => [
        'api_key' => env('NYTIMES_API_KEY'),
        'enabled' => true,
    ],
    // Add more sources here
];
