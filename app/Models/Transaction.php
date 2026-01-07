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

class Transaction extends Model
{
  use HasFactory, SoftDeletes, HasUuid, LogsActivity, Cacheable;

  protected $fillable = [
    'buyer_id',
    'post_id',
    'quantity',
    'total_price',
    'status',
  ];

  protected $hidden = [
    'id',
  ];

  protected $appends = [
    'can_be_cancelled',
    'can_be_reviewed',
  ];

  protected function casts(): array
  {
    return [
      'total_price' => 'decimal:2',
      'quantity' => 'integer',
    ];
  }

  /**
   * Check if transaction can be cancelled.
   */
  public function getCanBeCancelledAttribute(): bool
  {
    return in_array($this->status, ['pending', 'confirmed']);
  }

  /**
   * Check if transaction can be reviewed.
   */
  public function getCanBeReviewedAttribute(): bool
  {
    return $this->status === 'completed' && !$this->review;
  }

  /**
   * Scope: Pending transactions.
   */
  public function scopePending(Builder $query): Builder
  {
    return $query->where('status', 'pending');
  }

  /**
   * Scope: Completed transactions.
   */
  public function scopeCompleted(Builder $query): Builder
  {
    return $query->where('status', 'completed');
  }

  /**
   * Scope: Active transactions (pending or confirmed).
   */
  public function scopeActive(Builder $query): Builder
  {
    return $query->whereIn('status', ['pending', 'confirmed']);
  }

  // Relasi: Transaksi ini milik satu pembeli (user)
  public function buyer()
  {
    return $this->belongsTo(User::class, 'buyer_id');
  }

  // Relasi: Transaksi ini merujuk ke satu postingan makanan
  public function foodPost()
  {
    return $this->belongsTo(FoodPost::class, 'post_id');
  }

  // Relasi: Satu transaksi memiliki satu review
  public function review()
  {
    return $this->hasOne(Review::class);
  }

  /**
   * Clear cache when model is updated or deleted.
   */
  protected function clearKnownCacheKeys(): void
  {
    Cache::forget($this->getCacheKey('details'));
    Cache::forget('user:' . $this->buyer_id . ':transactions');
  }
}
