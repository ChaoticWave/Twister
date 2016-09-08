<?php
/**
 * Twister service default config file
 */
return [
    /** Twitter API version */
    'version'   => '1.1',
    /** OAuth endpoints */
    'endpoints' => [
        'application'     => ['rate_limit_status'],
        'account'         => [
            'settings',
            'verify_credentials',
            'update_delivery_device',
            'update_profile',
            'update_profile_background_image',
            'update_profile_image',
            'remove_profile_banner',
            'update_profile_banner',
        ],
        'blocks'          => ['list', 'id', 'create', 'destroy'],
        'direct_messages' => ['', 'sent', 'show', 'destroy', 'new'],
        'favorites'       => [],
        'friends'         => [],
        'geo'             => [],
        'help'            => ['configuration', 'languages', 'privacy', 'tos'],
        'lists'           => [],
        'media'           => [],
        'search'          => [],
        'statuses'        => [],
        'trend'           => [],
        'user'            => ['report_spam',],
    ],
    /** API keys, sourced from the environment */
    'secrets'   => [
        //  An "application" key and secret
        'consumer_key'    => env('TWISTER_CONSUMER_KEY'),
        'consumer_secret' => env('TWISTER_CONSUMER_SECRET'),
        //  An "access token" key and secret
        'client_id'       => env('TWISTER_CLIENT_ID'),
        'client_secret'   => env('TWISTER_CLIENT_SECRET'),
    ],
];
