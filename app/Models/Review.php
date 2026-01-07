<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use App\Traits\HasUuid;
use App\Traits\LogsActivity;
use App\Traits\Cacheable;

class Review extends Model
{
  use HasFactory, SoftDeletes, HasUuid, LogsActivity, Cacheable;

  protected $fillable = [
    'transaction_id',
    'rating',
    'review',
  ];

  protected $hidden = [
    'id',
  ];

  protected function casts(): array
  {
    return [
      'rating' => 'integer',
      'created_at' => 'datetime',
      'updated_at' => 'datetime',
    ];
  }

  // Relasi: Review ini milik satu transaksi
  public function transaction()
  {
    return $this->belongsTo(Transaction::class);
  }

  /**
   * Clear cache when model is updated or deleted.
   */
  protected function clearKnownCacheKeys(): void
  {
    Cache::forget($this->getCacheKey('details'));
    if ($this->transaction) {
      Cache::forget('post:' . $this->transaction->post_id . ':reviews');
    }
  }
}
