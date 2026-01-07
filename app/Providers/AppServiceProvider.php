<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\Model;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   */
  public function register(): void
  {
    // Register repositories
    $this->app->bind(
      \App\Repositories\UserRepository::class,
      fn($app) => new \App\Repositories\UserRepository(new \App\Models\User)
    );

    $this->app->bind(
      \App\Repositories\FoodPostRepository::class,
      fn($app) => new \App\Repositories\FoodPostRepository(new \App\Models\FoodPost)
    );

    $this->app->bind(
      \App\Repositories\TransactionRepository::class,
      fn($app) => new \App\Repositories\TransactionRepository(new \App\Models\Transaction)
    );

    $this->app->bind(
      \App\Repositories\ReviewRepository::class,
      fn($app) => new \App\Repositories\ReviewRepository()
    );

    $this->app->bind(
      \App\Repositories\ReportRepository::class,
      fn($app) => new \App\Repositories\ReportRepository()
    );

    // Register services
    $this->app->singleton(\App\Services\AuthService::class);
    $this->app->singleton(\App\Services\FoodPostService::class);
    $this->app->singleton(\App\Services\TransactionService::class);
    $this->app->singleton(\App\Services\ReviewService::class);
    $this->app->singleton(\App\Services\ReportService::class);
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    // Set default string length for database columns
    Schema::defaultStringLength(191);

    // Force HTTPS in production
    if ($this->app->environment('production')) {
      URL::forceScheme('https');
    }

    // Prevent lazy loading in development
    Model::preventLazyLoading($this->app->environment('production') === false);

    // Prevent silently discarding attributes
    Model::preventSilentlyDiscardingAttributes($this->app->environment('production') === false);

    // Prevent accessing missing attributes
    Model::preventAccessingMissingAttributes($this->app->environment('production') === false);

    // Share errors with all views for testing compatibility
    \Illuminate\Support\Facades\View::share('errors', session('errors', new \Illuminate\Support\ViewErrorBag()));
  }
}
