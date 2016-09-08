<?php
/**
 * Twister service default config file
 */
return [
    /** A user to target versus the consumer_key owner */
    'user'    => null,
    /** API keys, sourced from the environment */
    'secrets' => [
        //  An "application" key and secret
        'consumer_key'    => env('TWISTER_CONSUMER_KEY'),
        'consumer_secret' => env('TWISTER_CONSUMER_SECRET'),
        //  An "access token" key and secret
        'client_id'       => env('TWISTER_CLIENT_ID'),
        'client_secret'   => env('TWISTER_CLIENT_SECRET'),
    ],
];
