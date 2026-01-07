<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait Cacheable
{
  /**
   * Get the cache key for this model instance.
   */
  public function getCacheKey(string $suffix = ''): string
  {
    $key = strtolower(class_basename($this)) . ':' . ($this->uuid ?? $this->id);

    return $suffix ? "{$key}:{$suffix}" : $key;
  }

  /**
   * Clear cache for this model instance.
   */
  public function clearCache(): void
  {
    $pattern = strtolower(class_basename($this)) . ':' . ($this->uuid ?? $this->id) . '*';

    if (config('cache.default') === 'redis') {
      Cache::getRedis()->del(Cache::getRedis()->keys($pattern));
    } else {
      // For non-Redis cache, we'll need to track keys manually
      $this->clearKnownCacheKeys();
    }
  }

  /**
   * Clear known cache keys for this model.
   */
  protected function clearKnownCacheKeys(): void
  {
    // Override this method in models to clear specific cache keys
  }

  /**
   * Remember a value in cache with model-specific key.
   */
  public function cacheRemember(string $key, $ttl, \Closure $callback)
  {
    return Cache::remember($this->getCacheKey($key), $ttl, $callback);
  }
}
