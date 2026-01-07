<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use App\Traits\HasUuid;
use App\Traits\LogsActivity;
use App\Traits\Cacheable;

class User extends Authenticatable
{
  use HasFactory, Notifiable, SoftDeletes, HasUuid, LogsActivity, Cacheable;

  protected $fillable = [
    'name',
    'email',
    'whatsapp_number',
    'password',
    'is_verified',
    'is_admin',
    'suspended_at',
    'suspension_reason',
  ];

  protected $hidden = [
    'password',
    'remember_token',
    'id', // Hide auto-increment ID, only expose UUID
  ];

  protected $appends = [
    'is_suspended',
  ];

  protected function casts(): array
  {
    return [
      'email_verified_at' => 'datetime',
      'password' => 'hashed',
      'is_verified' => 'boolean',
      'is_admin' => 'boolean',
      'suspended_at' => 'datetime',
    ];
  }

  /**
   * Check if user is suspended.
   */
  public function getIsSuspendedAttribute(): bool
  {
    return !is_null($this->suspended_at);
  }

  /**
   * Suspend this user.
   */
  public function suspend(string $reason = null): bool
  {
    return $this->update([
      'suspended_at' => now(),
      'suspension_reason' => $reason,
    ]);
  }

  /**
   * Unsuspend this user.
   */
  public function unsuspend(): bool
  {
    return $this->update([
      'suspended_at' => null,
      'suspension_reason' => null,
    ]);
  }

  // Relasi: Satu user bisa memiliki banyak postingan makanan
  public function foodPosts()
  {
    return $this->hasMany(FoodPost::class);
  }

  // Relasi: Satu user bisa melakukan banyak transaksi (sebagai pembeli)
  public function transactions()
  {
    return $this->hasMany(Transaction::class, 'buyer_id');
  }

  // Relasi: Satu user bisa membuat banyak laporan
  public function reports()
  {
    return $this->hasMany(Report::class, 'reporter_id');
  }

  // Relasi: Activity logs
  public function activityLogs()
  {
    return $this->hasMany(ActivityLog::class);
  }

  /**
   * Clear cache when model is updated or deleted.
   */
  protected function clearKnownCacheKeys(): void
  {
    Cache::forget($this->getCacheKey('profile'));
    Cache::forget($this->getCacheKey('posts'));
    Cache::forget($this->getCacheKey('transactions'));
  }
}
