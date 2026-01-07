<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::table('activity_logs', function (Blueprint $table) {
      $table->uuid('uuid')->unique()->after('id');
      $table->string('ip_address', 45)->nullable();
      $table->text('user_agent')->nullable();
      $table->json('properties')->nullable();
      $table->string('event_type', 50)->nullable();
      $table->index('user_id');
      $table->index('event_type');
      $table->index('created_at');
    });
  }

  public function down(): void
  {
    Schema::table('activity_logs', function (Blueprint $table) {
      $table->dropColumn(['uuid', 'ip_address', 'user_agent', 'properties', 'event_type']);
      $table->dropIndex(['activity_logs_user_id_index']);
      $table->dropIndex(['activity_logs_event_type_index']);
      $table->dropIndex(['activity_logs_created_at_index']);
    });
  }
};
