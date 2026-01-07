<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

trait LogsActivity
{
  protected static function bootLogsActivity(): void
  {
    static::created(function ($model) {
      self::logActivity($model, 'created');
    });

    static::updated(function ($model) {
      self::logActivity($model, 'updated');
    });

    static::deleted(function ($model) {
      self::logActivity($model, 'deleted');
    });
  }

  protected static function logActivity($model, string $action): void
  {
    try {
      ActivityLog::create([
        'user_id' => Auth::id(),
        'action' => $action . ' ' . class_basename($model),
        'event_type' => $action,
        'ip_address' => request()->ip(),
        'user_agent' => request()->userAgent(),
        'properties' => [
          'model' => get_class($model),
          'model_uuid' => $model->uuid ?? null,
          'changes' => method_exists($model, 'getChanges') ? $model->getChanges() : [],
        ],
      ]);
    } catch (\Exception $e) {
      Log::warning('Failed to log activity: ' . $e->getMessage());
    }
  }
}
