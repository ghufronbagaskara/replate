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

class Report extends Model
{
  use HasFactory, SoftDeletes, HasUuid, LogsActivity, Cacheable;

  protected $fillable = [
    'post_id',
    'reporter_id',
    'reason',
    'other_reason',
    'status',
    'admin_notes',
  ];

  protected $hidden = [
    'id',
  ];

  protected function casts(): array
  {
    return [
      'created_at' => 'datetime',
      'updated_at' => 'datetime',
    ];
  }

  /**
   * Scope: Pending reports.
   */
  public function scopePending(Builder $query): Builder
  {
    return $query->where('status', 'pending');
  }

  /**
   * Scope: Reviewed reports.
   */
  public function scopeReviewed(Builder $query): Builder
  {
    return $query->where('status', 'reviewed');
  }

  /**
   * Mark report as reviewed.
   */
  public function markAsReviewed(string $adminNotes = null): bool
  {
    return $this->update([
      'status' => 'reviewed',
      'admin_notes' => $adminNotes,
    ]);
  }

  /**
   * Resolve report.
   */
  public function resolve(string $adminNotes = null): bool
  {
    return $this->update([
      'status' => 'resolved',
      'admin_notes' => $adminNotes,
    ]);
  }

  /**
   * Dismiss report.
   */
  public function dismiss(string $adminNotes = null): bool
  {
    return $this->update([
      'status' => 'dismissed',
      'admin_notes' => $adminNotes,
    ]);
  }

  // Relasi: Laporan ini merujuk ke satu postingan
  public function foodPost()
  {
    return $this->belongsTo(FoodPost::class, 'post_id');
  }

  // Relasi: Laporan ini dibuat oleh satu user
  public function reporter()
  {
    return $this->belongsTo(User::class, 'reporter_id');
  }

  /**
   * Clear cache when model is updated or deleted.
   */
  protected function clearKnownCacheKeys(): void
  {
    Cache::forget($this->getCacheKey('details'));
    Cache::forget('admin:reports:pending');
  }
}
