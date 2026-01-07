<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use App\Traits\HasUuid;
use App\Traits\LogsActivity;
use App\Traits\Cacheable;
use Illuminate\Database\Eloquent\Builder;

class FoodPost extends Model
{
  use HasFactory, SoftDeletes, HasUuid, LogsActivity, Cacheable;

  protected $fillable = [
    'user_id',
    'title',
    'description',
    'price',
    'image_path',
    'location',
    'is_free',
    'expires_at',
    'stock',
    'status',
  ];

  protected $hidden = [
    'id', // Hide auto-increment ID
  ];

  protected $appends = [
    'is_expired',
    'is_available',
  ];

  protected function casts(): array
  {
    return [
      'is_free' => 'boolean',
      'expires_at' => 'datetime',
      'price' => 'decimal:2',
    ];
  }

  /**
   * Check if post is expired.
   */
  public function getIsExpiredAttribute(): bool
  {
    return $this->expires_at->isPast();
  }

  /**
   * Check if post is available.
   */
  public function getIsAvailableAttribute(): bool
  {
    return $this->status === 'available'
      && !$this->is_expired
      && $this->stock > 0;
  }

  /**
   * Scope: Available posts only.
   */
  public function scopeAvailable(Builder $query): Builder
  {
    return $query->where('status', 'available')
      ->where('expires_at', '>', now())
      ->where('stock', '>', 0);
  }

  /**
   * Scope: Free posts only.
   */
  public function scopeFree(Builder $query): Builder
  {
    return $query->where('is_free', true);
  }

  /**
   * Scope: Posts by price range.
   */
  public function scopePriceRange(Builder $query, float $min, float $max): Builder
  {
    return $query->whereBetween('price', [$min, $max]);
  }

  /**
   * Scope: Posts by location.
   */
  public function scopeByLocation(Builder $query, string $location): Builder
  {
    return $query->where('location', 'like', '%' . $location . '%');
  }

  // Relasi: Postingan ini milik satu user
  public function user()
  {
    return $this->belongsTo(User::class);
  }

  // Relasi: Satu postingan bisa memiliki banyak transaksi
  public function transactions()
  {
    return $this->hasMany(Transaction::class, 'post_id');
  }

  // Relasi: Satu postingan bisa memiliki banyak laporan
  public function reports()
  {
    return $this->hasMany(Report::class, 'post_id');
  }

  /**
   * Clear cache when model is updated or deleted.
   */
  protected function clearKnownCacheKeys(): void
  {
    Cache::forget($this->getCacheKey('details'));
    Cache::forget('feed:available');
    Cache::forget('feed:free');
  }
}
