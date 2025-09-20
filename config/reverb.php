<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Reverb Server
    |--------------------------------------------------------------------------
    */

    'default' => env('REVERB_SERVER', 'reverb'),

    /*
    |--------------------------------------------------------------------------
    | Reverb Servers
    |--------------------------------------------------------------------------
    */

    'servers' => [
        'reverb' => [
            'host' => env('REVERB_SERVER_HOST', '127.0.0.1'), // local binding
            'port' => env('REVERB_SERVER_PORT', 8080),        // Reverb internal port
            'hostname' => env('REVERB_HOST', 'flyertrade.com'), // public hostname
            'options' => [
                'tls' => [
                    'local_cert' => env('REVERB_TLS_CERT_PATH'),
                    'local_pk'   => env('REVERB_TLS_KEY_PATH'),
                    'local_ca'   => env('REVERB_TLS_CA_PATH'),
                ],
            ],
            'max_request_size' => env('REVERB_MAX_REQUEST_SIZE', 10000),

            // Scaling (Redis pub-sub)
            'scaling' => [
                'enabled'  => env('REVERB_SCALING_ENABLED', false),
                'channel'  => env('REVERB_SCALING_CHANNEL', 'reverb'),
                'server'   => [
                    'url'      => env('REDIS_URL'),
                    'host'     => env('REDIS_HOST', '127.0.0.1'),
                    'port'     => env('REDIS_PORT', 6379),
                    'username' => env('REDIS_USERNAME'),
                    'password' => env('REDIS_PASSWORD'),
                    'database' => env('REDIS_DB', 0),
                    'timeout'  => env('REDIS_TIMEOUT', 60),
                ],
            ],

            'pulse_ingest_interval'     => env('REVERB_PULSE_INGEST_INTERVAL', 15),
            'telescope_ingest_interval' => env('REVERB_TELESCOPE_INGEST_INTERVAL', 15),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Reverb Applications
    |--------------------------------------------------------------------------
    */

    'apps' => [
        'provider' => 'config',
        'apps' => [
            [
                'key'    => env('REVERB_APP_KEY'),
                'secret' => env('REVERB_APP_SECRET'),
                'app_id' => env('REVERB_APP_ID'),
                'options' => [
                    'host'   => env('REVERB_HOST', 'flyertrade.com'),
                    'port'   => env('REVERB_PORT', 443),
                    'scheme' => env('REVERB_SCHEME', 'https'),
                    'useTLS' => true,
                ],
                'allowed_origins' => ['*'],
            ],
        ],
    ],

];
