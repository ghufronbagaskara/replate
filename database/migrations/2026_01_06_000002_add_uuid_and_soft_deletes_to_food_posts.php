<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::table('food_posts', function (Blueprint $table) {
      $table->uuid('uuid')->unique()->after('id');
      $table->softDeletes();
      $table->index('status');
      $table->index('expires_at');
      $table->index(['status', 'expires_at']);
      $table->index('created_at');
    });
  }

  public function down(): void
  {
    Schema::table('food_posts', function (Blueprint $table) {
      $table->dropColumn(['uuid', 'deleted_at']);
      $table->dropIndex(['food_posts_status_index']);
      $table->dropIndex(['food_posts_expires_at_index']);
      $table->dropIndex(['food_posts_status_expires_at_index']);
      $table->dropIndex(['food_posts_created_at_index']);
    });
  }
};
