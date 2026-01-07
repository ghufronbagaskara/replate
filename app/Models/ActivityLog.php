<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Builder;

class ActivityLog extends Model
{
  use HasFactory, HasUuid;

  public $timestamps = false;

  protected $fillable = [
    'user_id',
    'action',
    'event_type',
    'ip_address',
    'user_agent',
    'properties',
  ];

  protected $hidden = [
    'id',
  ];

  protected function casts(): array
  {
    return [
      'properties' => 'array',
      'created_at' => 'datetime',
    ];
  }

  /**
   * Scope: Filter by event type.
   */
  public function scopeByEventType(Builder $query, string $eventType): Builder
  {
    return $query->where('event_type', $eventType);
  }

  /**
   * Scope: Recent logs.
   */
  public function scopeRecent(Builder $query, int $days = 7): Builder
  {
    return $query->where('created_at', '>=', now()->subDays($days));
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
