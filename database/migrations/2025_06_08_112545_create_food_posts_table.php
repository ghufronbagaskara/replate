<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('food_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title', 150);
            $table->text('description')->nullable();
            $table->integer('price');
            $table->string('image_path')->nullable();
            $table->string('location')->nullable();
            $table->boolean('is_free')->default(false);
            $table->dateTime('expires_at');
            $table->integer('stock');
            $table->enum('status', ['available', 'expired', 'taken_down'])->default('available');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('food_posts');
    }
};
