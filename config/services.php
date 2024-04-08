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

    'openpagerank' => [
        'url' => env('OPENPAGERANK_URL', 'https://openpagerank.com/api/v1.0/getPageRank'),
        'api_key' => env('OPENPAGERANK_API_KEY', 'ws48csg0cow0w4kg0gck4oc8wo80kcg8w0g4s08k'),
    ],

    'github' => [
        'domains_list_url' => env('GITHUB_DOMAINS_LIST_URL', 'https://raw.githubusercontent.com/Kikobeats/top-sites/master/top-sites.json'),
    ]

];
