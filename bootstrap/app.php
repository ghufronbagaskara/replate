<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
  ->withRouting(
    web: __DIR__ . '/../routes/web.php',
    commands: __DIR__ . '/../routes/console.php',
    health: '/up',
  )
  ->withMiddleware(function (Middleware $middleware) {
    // Global middleware
    $middleware->append(\App\Http\Middleware\SecurityHeaders::class);

    // Middleware alias
    $middleware->alias([
      'admin' => \App\Http\Middleware\AdminMiddleware::class,
      'check.suspended' => \App\Http\Middleware\CheckSuspended::class,
      'log.activity' => \App\Http\Middleware\LogUserActivity::class,
      'api.rate.limit' => \App\Http\Middleware\ApiRateLimiter::class,
    ]);

    // Middleware groups
    $middleware->group('web', [
      \App\Http\Middleware\CheckSuspended::class,
    ]);
  })
  ->withExceptions(function (Exceptions $exceptions) {
    //
  })
  ->create();
