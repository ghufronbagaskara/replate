<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\RateLimiter;

class ApiRateLimiter
{
  /**
   * Handle an incoming request.
   */
  public function handle(Request $request, Closure $next, string $limit = '60'): Response
  {
    $key = $this->resolveRequestSignature($request);

    if (RateLimiter::tooManyAttempts($key, $limit)) {
      return response()->json([
        'success' => false,
        'message' => 'Terlalu banyak permintaan. Silakan coba lagi nanti.',
      ], 429);
    }

    RateLimiter::hit($key, 60);

    $response = $next($request);

    return $response->withHeaders([
      'X-RateLimit-Limit' => $limit,
      'X-RateLimit-Remaining' => RateLimiter::remaining($key, $limit),
    ]);
  }

  /**
   * Resolve request signature.
   */
  protected function resolveRequestSignature(Request $request): string
  {
    if ($user = $request->user()) {
      return sha1($user->id);
    }

    return sha1($request->ip());
  }
}
