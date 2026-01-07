<?php

return [

  /*
    |--------------------------------------------------------------------------
    | Security Settings
    |--------------------------------------------------------------------------
    */

  'headers' => [
    'x_frame_options' => 'SAMEORIGIN',
    'x_content_type_options' => 'nosniff',
    'x_xss_protection' => '1; mode=block',
    'referrer_policy' => 'strict-origin-when-cross-origin',
    'permissions_policy' => 'geolocation=(), microphone=(), camera=()',
    'hsts' => [
      'enabled' => env('SECURITY_HSTS_ENABLED', true),
      'max_age' => 31536000,
      'include_subdomains' => true,
    ],
  ],

  /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    */

  'rate_limit' => [
    'login' => [
      'attempts' => 5,
      'decay_minutes' => 15,
    ],
    'api' => [
      'attempts' => 60,
      'decay_minutes' => 1,
    ],
    'registration' => [
      'attempts' => 3,
      'decay_minutes' => 60,
    ],
  ],

  /*
    |--------------------------------------------------------------------------
    | Password Requirements
    |--------------------------------------------------------------------------
    */

  'password' => [
    'min_length' => 8,
    'require_uppercase' => true,
    'require_lowercase' => true,
    'require_numbers' => true,
    'require_special_chars' => false,
  ],

  /*
    |--------------------------------------------------------------------------
    | File Upload Settings
    |--------------------------------------------------------------------------
    */

  'upload' => [
    'max_file_size' => 2048, // KB
    'allowed_image_types' => ['jpeg', 'png', 'jpg', 'webp'],
    'image_quality' => 85,
  ],

  /*
    |--------------------------------------------------------------------------
    | Activity Logging
    |--------------------------------------------------------------------------
    */

  'activity_log' => [
    'enabled' => env('ACTIVITY_LOG_ENABLED', true),
    'retention_days' => 90,
  ],

];
