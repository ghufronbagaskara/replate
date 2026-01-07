<?php

return [

  /*
    |--------------------------------------------------------------------------
    | Application Settings
    |--------------------------------------------------------------------------
    */

  'max_food_price' => env('MAX_FOOD_PRICE', 35000),
  'max_stock_quantity' => env('MAX_STOCK_QUANTITY', 1000),
  'max_order_quantity' => env('MAX_ORDER_QUANTITY', 100),

  /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */

  'pagination' => [
    'feed_per_page' => 9,
    'admin_posts_per_page' => 20,
    'admin_users_per_page' => 20,
    'admin_reports_per_page' => 20,
    'transactions_per_page' => 10,
  ],

  /*
    |--------------------------------------------------------------------------
    | Transaction Settings
    |--------------------------------------------------------------------------
    */

  'transaction' => [
    'auto_cancel_pending_after_hours' => 24,
    'auto_complete_confirmed_after_days' => 7,
  ],

  /*
    |--------------------------------------------------------------------------
    | Review Settings
    |--------------------------------------------------------------------------
    */

  'review' => [
    'min_rating' => 1,
    'max_rating' => 5,
    'max_review_length' => 500,
  ],

  /*
    |--------------------------------------------------------------------------
    | Report Settings
    |--------------------------------------------------------------------------
    */

  'report' => [
    'reasons' => ['expired', 'fake_photo', 'misleading', 'other'],
    'max_other_reason_length' => 500,
  ],

];
