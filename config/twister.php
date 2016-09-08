<?php
/**
 * Twister service default config file
 */
return [
    /** Twitter API version */
    'version'     => '1.1',
    /** If true, enforce use of https */
    'enforce_ssl' => true,
    /** OAuth endpoints */
    'endpoints'   => [
        'authenticate'  => 'https://api.twitter.com/oauth/authenticate',
        'authorize'     => 'https://api.twitter.com/oauth/authorize',
        'access_token'  => 'https://api.twitter.com/oauth/access_token',
        'request_token' => 'https://api.twitter.com/oauth/request_token',
    ],
    /** API keys, sourced from the environment */
    'secrets'     => [
        //  An "application" key and secret
        'consumer_key'    => env('TWISTER_CONSUMER_KEY'),
        'consumer_secret' => env('TWISTER_CONSUMER_SECRET'),
        //  An "access token" key and secret
        'client_id'       => env('TWISTER_CLIENT_ID'),
        'client_secret'   => env('TWISTER_CLIENT_SECRET'),
    ],
];
