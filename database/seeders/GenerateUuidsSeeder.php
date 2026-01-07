<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\FoodPost;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class GenerateUuidsSeeder extends Seeder
{
  /**
   * Run the database seeds.
   * 
   * This seeder generates UUIDs for all existing records that don't have one.
   * Run this after adding UUID columns to existing tables.
   */
  public function run(): void
  {
    $this->command->info('Generating UUIDs for existing records...');

    // Users
    $usersCount = User::whereNull('uuid')->count();
    if ($usersCount > 0) {
      $this->command->info("Generating UUIDs for {$usersCount} users...");
      User::whereNull('uuid')->each(function ($user) {
        $user->uuid = (string) Str::uuid();
        $user->save();
      });
      $this->command->info("✓ Generated UUIDs for {$usersCount} users");
    }

    // Food Posts
    $postsCount = FoodPost::whereNull('uuid')->count();
    if ($postsCount > 0) {
      $this->command->info("Generating UUIDs for {$postsCount} food posts...");
      FoodPost::whereNull('uuid')->each(function ($post) {
        $post->uuid = (string) Str::uuid();
        $post->save();
      });
      $this->command->info("✓ Generated UUIDs for {$postsCount} food posts");
    }

    // Transactions
    $transactionsCount = \App\Models\Transaction::whereNull('uuid')->count();
    if ($transactionsCount > 0) {
      $this->command->info("Generating UUIDs for {$transactionsCount} transactions...");
      \App\Models\Transaction::whereNull('uuid')->each(function ($transaction) {
        $transaction->uuid = (string) Str::uuid();
        // Calculate total_price if not set
        if (is_null($transaction->total_price) && $transaction->foodPost) {
          $transaction->total_price = $transaction->foodPost->price * $transaction->quantity;
        }
        $transaction->save();
      });
      $this->command->info("✓ Generated UUIDs for {$transactionsCount} transactions");
    }

    // Reviews
    $reviewsCount = \App\Models\Review::whereNull('uuid')->count();
    if ($reviewsCount > 0) {
      $this->command->info("Generating UUIDs for {$reviewsCount} reviews...");
      \App\Models\Review::whereNull('uuid')->each(function ($review) {
        $review->uuid = (string) Str::uuid();
        $review->save();
      });
      $this->command->info("✓ Generated UUIDs for {$reviewsCount} reviews");
    }

    // Reports
    $reportsCount = \App\Models\Report::whereNull('uuid')->count();
    if ($reportsCount > 0) {
      $this->command->info("Generating UUIDs for {$reportsCount} reports...");
      \App\Models\Report::whereNull('uuid')->each(function ($report) {
        $report->uuid = (string) Str::uuid();
        if (is_null($report->status)) {
          $report->status = 'pending';
        }
        $report->save();
      });
      $this->command->info("✓ Generated UUIDs for {$reportsCount} reports");
    }

    // Activity Logs
    $logsCount = \App\Models\ActivityLog::whereNull('uuid')->count();
    if ($logsCount > 0) {
      $this->command->info("Generating UUIDs for {$logsCount} activity logs...");
      \App\Models\ActivityLog::whereNull('uuid')->each(function ($log) {
        $log->uuid = (string) Str::uuid();
        $log->save();
      });
      $this->command->info("✓ Generated UUIDs for {$logsCount} activity logs");
    }

    $this->command->info('');
    $this->command->info('✓ All UUIDs generated successfully!');
  }
}
