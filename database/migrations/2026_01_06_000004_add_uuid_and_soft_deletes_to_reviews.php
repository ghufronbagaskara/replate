<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::table('reviews', function (Blueprint $table) {
      $table->uuid('uuid')->unique()->after('id');
      $table->softDeletes();
      $table->timestamp('updated_at')->nullable();
      $table->index('transaction_id');
      $table->index('rating');
    });
  }

  public function down(): void
  {
    Schema::table('reviews', function (Blueprint $table) {
      $table->dropColumn(['uuid', 'deleted_at', 'updated_at']);
      $table->dropIndex(['reviews_transaction_id_index']);
      $table->dropIndex(['reviews_rating_index']);
    });
  }
};
