<?php

return [

  /*
    |--------------------------------------------------------------------------
    | Default Cache Store
    |--------------------------------------------------------------------------
    */
  'default' => env('CACHE_STORE', 'database'),

  /*
    |--------------------------------------------------------------------------
    | Cache Stores
    |--------------------------------------------------------------------------
    */
  'stores' => [
    'database' => [
      'driver' => 'database',
      'table' => env('CACHE_TABLE', 'cache'),
      'connection' => env('CACHE_DATABASE_CONNECTION'),
      'lock_connection' => env('CACHE_LOCK_CONNECTION'),
    ],

    'redis' => [
      'driver' => 'redis',
      'connection' => env('CACHE_REDIS_CONNECTION', 'cache'),
      'lock_connection' => env('CACHE_LOCK_CONNECTION', 'default'),
    ],
  ],

  /*
    |--------------------------------------------------------------------------
    | Cache Key Prefix
    |--------------------------------------------------------------------------
    */
  'prefix' => env('CACHE_PREFIX', 'replate_cache'),

  /*
    |--------------------------------------------------------------------------
    | Cache TTL (Time To Live) in seconds
    |--------------------------------------------------------------------------
    */
  'ttl' => [
    'short' => 300,      // 5 minutes
    'medium' => 1800,    // 30 minutes
    'long' => 3600,      // 1 hour
    'day' => 86400,      // 24 hours
    'week' => 604800,    // 7 days
  ],

];
