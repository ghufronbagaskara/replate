<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::table('users', function (Blueprint $table) {
      $table->uuid('uuid')->unique()->after('id');
      $table->softDeletes();
      $table->string('suspended_at')->nullable();
      $table->text('suspension_reason')->nullable();
      $table->index('email');
      $table->index('is_admin');
      $table->index('is_verified');
    });
  }

  public function down(): void
  {
    Schema::table('users', function (Blueprint $table) {
      $table->dropColumn(['uuid', 'deleted_at', 'suspended_at', 'suspension_reason']);
      $table->dropIndex(['users_email_index']);
      $table->dropIndex(['users_is_admin_index']);
      $table->dropIndex(['users_is_verified_index']);
    });
  }
};
