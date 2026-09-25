<?php

return [
    'seed_super_admin_email' => env('APP_ENV') === 'local' ? env('SEED_SUPER_ADMIN_EMAIL') : null,
    'seed_super_admin_password' => env('APP_ENV') === 'local' ? env('SEED_SUPER_ADMIN_PASSWORD') : null,
    'tracking_session_minutes' => 30,
    'require_admin_mfa' => (bool) env('REQUIRE_ADMIN_MFA', env('APP_ENV') === 'production'),
];
