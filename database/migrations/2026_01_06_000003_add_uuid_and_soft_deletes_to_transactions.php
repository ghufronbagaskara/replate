<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::table('transactions', function (Blueprint $table) {
      $table->uuid('uuid')->unique()->after('id');
      $table->softDeletes();
      $table->decimal('total_price', 10, 2)->nullable()->after('quantity');
      $table->index('status');
      $table->index(['buyer_id', 'status']);
      $table->index(['post_id', 'status']);
      $table->index('created_at');
    });
  }

  public function down(): void
  {
    Schema::table('transactions', function (Blueprint $table) {
      $table->dropColumn(['uuid', 'deleted_at', 'total_price']);
      $table->dropIndex(['transactions_status_index']);
      $table->dropIndex(['transactions_buyer_id_status_index']);
      $table->dropIndex(['transactions_post_id_status_index']);
      $table->dropIndex(['transactions_created_at_index']);
    });
  }
};
