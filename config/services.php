<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'smile'=>[
        'api_key'=>env('SMILE_API_KEY'),
        'partner_id'=>env('SMILE_PARTNER_ID'),
    ],
    'appruve'=>[
        'api_key'=>env('APPRUVE_API_KEY')
    ],
    'credequity'=>[
        'api_key'=>env('CREDEQUITY_API_KEY'),
        'base_url'=>env('CREDEQUITY_BASE_URL')
    ]

];
