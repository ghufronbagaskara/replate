<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ActivityLog;
use App\Models\FoodPost;
use Carbon\Carbon;

class CleanupOldData extends Command
{
  /**
   * The name and signature of the console command.
   */
  protected $signature = 'cleanup:old-data
                            {--days=90 : Number of days to keep activity logs}
                            {--expired-posts : Also cleanup expired food posts}';

  /**
   * The console command description.
   */
  protected $description = 'Cleanup old activity logs and optionally expired food posts';

  /**
   * Execute the console command.
   */
  public function handle(): int
  {
    $days = (int) $this->option('days');
    $cleanupPosts = $this->option('expired-posts');

    $this->info("Starting cleanup process...");
    $this->info("Retention period: {$days} days");

    // Cleanup activity logs
    $this->cleanupActivityLogs($days);

    // Cleanup expired posts if requested
    if ($cleanupPosts) {
      $this->cleanupExpiredPosts();
    }

    $this->info('Cleanup completed successfully!');

    return Command::SUCCESS;
  }

  /**
   * Cleanup old activity logs.
   */
  protected function cleanupActivityLogs(int $days): void
  {
    $this->info('Cleaning up activity logs...');

    $cutoffDate = Carbon::now()->subDays($days);
    $count = ActivityLog::where('created_at', '<', $cutoffDate)->count();

    if ($count > 0) {
      ActivityLog::where('created_at', '<', $cutoffDate)->delete();
      $this->line("✓ Deleted {$count} old activity logs");
    } else {
      $this->line('✓ No old activity logs to delete');
    }
  }

  /**
   * Cleanup expired food posts.
   */
  protected function cleanupExpiredPosts(): void
  {
    $this->info('Cleaning up expired food posts...');

    $count = FoodPost::where('status', 'available')
      ->where('expires_at', '<', Carbon::now())
      ->count();

    if ($count > 0) {
      FoodPost::where('status', 'available')
        ->where('expires_at', '<', Carbon::now())
        ->update(['status' => 'expired']);

      $this->line("✓ Marked {$count} posts as expired");
    } else {
      $this->line('✓ No expired posts to update');
    }
  }
}
