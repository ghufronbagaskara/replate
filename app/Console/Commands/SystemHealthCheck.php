<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SystemHealthCheck extends Command
{
  /**
   * The name and signature of the console command.
   */
  protected $signature = 'system:health-check';

  /**
   * The console command description.
   */
  protected $description = 'Check system health and report any issues';

  /**
   * Execute the console command.
   */
  public function handle(): int
  {
    $this->info('Running system health check...');
    $this->newLine();

    $allHealthy = true;

    // Check database connection
    $allHealthy = $this->checkDatabase() && $allHealthy;

    // Check cache
    $allHealthy = $this->checkCache() && $allHealthy;

    // Check storage permissions
    $allHealthy = $this->checkStoragePermissions() && $allHealthy;

    // Check required environment variables
    $allHealthy = $this->checkEnvironmentVariables() && $allHealthy;

    // Check UUID integrity
    $allHealthy = $this->checkUuidIntegrity() && $allHealthy;

    $this->newLine();

    if ($allHealthy) {
      $this->info('✓ All health checks passed!');
      return Command::SUCCESS;
    } else {
      $this->error('✗ Some health checks failed. Please review the issues above.');
      return Command::FAILURE;
    }
  }

  /**
   * Check database connection.
   */
  protected function checkDatabase(): bool
  {
    $this->info('Checking database connection...');

    try {
      DB::connection()->getPdo();
      $this->line('✓ Database connection: OK');
      return true;
    } catch (\Exception $e) {
      $this->error('✗ Database connection: FAILED');
      $this->error('  Error: ' . $e->getMessage());
      return false;
    }
  }

  /**
   * Check cache connection.
   */
  protected function checkCache(): bool
  {
    $this->info('Checking cache connection...');

    try {
      $testKey = 'health_check_' . time();
      Cache::put($testKey, 'test', 60);
      $value = Cache::get($testKey);
      Cache::forget($testKey);

      if ($value === 'test') {
        $this->line('✓ Cache connection: OK');
        return true;
      } else {
        $this->error('✗ Cache connection: FAILED (value mismatch)');
        return false;
      }
    } catch (\Exception $e) {
      $this->error('✗ Cache connection: FAILED');
      $this->error('  Error: ' . $e->getMessage());
      return false;
    }
  }

  /**
   * Check storage permissions.
   */
  protected function checkStoragePermissions(): bool
  {
    $this->info('Checking storage permissions...');

    $directories = [
      storage_path('app'),
      storage_path('framework'),
      storage_path('logs'),
      base_path('bootstrap/cache'),
    ];

    $allWritable = true;

    foreach ($directories as $dir) {
      if (!is_writable($dir)) {
        $this->error("✗ Directory not writable: {$dir}");
        $allWritable = false;
      }
    }

    if ($allWritable) {
      $this->line('✓ Storage permissions: OK');
    }

    return $allWritable;
  }

  /**
   * Check required environment variables.
   */
  protected function checkEnvironmentVariables(): bool
  {
    $this->info('Checking environment variables...');

    $required = [
      'APP_NAME',
      'APP_ENV',
      'APP_KEY',
      'APP_URL',
      'DB_CONNECTION',
      'DB_HOST',
      'DB_DATABASE',
    ];

    $missing = [];

    foreach ($required as $var) {
      if (empty(env($var))) {
        $missing[] = $var;
      }
    }

    if (empty($missing)) {
      $this->line('✓ Environment variables: OK');
      return true;
    } else {
      $this->error('✗ Missing environment variables:');
      foreach ($missing as $var) {
        $this->error("  - {$var}");
      }
      return false;
    }
  }

  /**
   * Check UUID integrity.
   */
  protected function checkUuidIntegrity(): bool
  {
    $this->info('Checking UUID integrity...');

    $models = [
      'User' => \App\Models\User::class,
      'FoodPost' => \App\Models\FoodPost::class,
      'Transaction' => \App\Models\Transaction::class,
      'Review' => \App\Models\Review::class,
      'Report' => \App\Models\Report::class,
    ];

    $issues = [];

    foreach ($models as $name => $class) {
      $missingUuids = $class::whereNull('uuid')->count();
      if ($missingUuids > 0) {
        $issues[] = "{$name}: {$missingUuids} records missing UUID";
      }
    }

    if (empty($issues)) {
      $this->line('✓ UUID integrity: OK');
      return true;
    } else {
      $this->error('✗ UUID integrity issues:');
      foreach ($issues as $issue) {
        $this->error("  - {$issue}");
      }
      $this->line('  Run: php artisan db:seed --class=GenerateUuidsSeeder');
      return false;
    }
  }
}
