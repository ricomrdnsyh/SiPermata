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

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'sso' => [
        'x_token' => env('SSO_X_TOKEN'),
        'dev_id' => env('SSO_DEV_ID'),
        'api_url' => env('SSO_API_URL', 'http://sso.unuja.ac.id:8080'),
        'public_url' => env('SSO_PUBLIC_URL', 'https://sso.unuja.ac.id'),
        'authorize_url' => env('SSO_AUTHORIZE_URL', 'http://sso.unuja.ac.id:8080/portal/data/authorize'),
        'data_url' => env('SSO_DATA_URL', 'http://sso.unuja.ac.id:8080/portal/data/data'),
        'me_url' => env('SSO_ME_URL', 'http://sso.unuja.ac.id:8080/portal/me/' . env('SSO_DEV_ID', '8ZiVo95nM1xUJzhA')),
    ],

];
