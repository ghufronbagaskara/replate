<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::table('reports', function (Blueprint $table) {
      $table->uuid('uuid')->unique()->after('id');
      $table->softDeletes();
      $table->timestamp('updated_at')->nullable();
      $table->enum('status', ['pending', 'reviewed', 'resolved', 'dismissed'])->default('pending');
      $table->text('admin_notes')->nullable();
      $table->index(['post_id', 'status']);
      $table->index('reporter_id');
      $table->index('reason');
    });
  }

  public function down(): void
  {
    Schema::table('reports', function (Blueprint $table) {
      $table->dropColumn(['uuid', 'deleted_at', 'updated_at', 'status', 'admin_notes']);
      $table->dropIndex(['reports_post_id_status_index']);
      $table->dropIndex(['reports_reporter_id_index']);
      $table->dropIndex(['reports_reason_index']);
    });
  }
};
