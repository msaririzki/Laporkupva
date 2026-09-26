<?php

$reverbScheme = env('VITE_REVERB_SCHEME', env('REVERB_SCHEME', 'https'));
$reverbPort = (int) env('VITE_REVERB_PORT', $reverbScheme === 'https' ? 443 : 8080);

return [
    'broadcasting' => [
        'echo' => [
            'broadcaster' => 'pusher',
            'key' => env('REVERB_APP_KEY', 'tambora-realtime'),
            'wsHost' => env('VITE_REVERB_HOST', parse_url((string) env('APP_URL'), PHP_URL_HOST)),
            'wsPort' => $reverbPort,
            'wssPort' => $reverbPort,
            'authEndpoint' => '/broadcasting/auth',
            'disableStats' => true,
            'encrypted' => $reverbScheme === 'https',
            'forceTLS' => $reverbScheme === 'https',
            'enabledTransports' => ['ws', 'wss'],
        ],
    ],
];
