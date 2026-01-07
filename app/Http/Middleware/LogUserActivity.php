<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ActivityLog;

class LogUserActivity
{
  /**
   * Handle an incoming request.
   */
  public function handle(Request $request, Closure $next): Response
  {
    $response = $next($request);

    if (Auth::check()) {
      $this->logActivity($request);
    }

    return $response;
  }

  /**
   * Log user activity.
   */
  protected function logActivity(Request $request): void
  {
    try {
      $action = $this->getActionDescription($request);

      if ($action) {
        ActivityLog::create([
          'user_id' => Auth::id(),
          'action' => $action,
          'event_type' => $request->method(),
          'ip_address' => $request->ip(),
          'user_agent' => $request->userAgent(),
          'properties' => [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'route' => $request->route()?->getName(),
          ],
        ]);
      }
    } catch (\Exception $e) {
      Log::warning('Failed to log activity: ' . $e->getMessage());
    }
  }

  /**
   * Get action description based on request.
   */
  protected function getActionDescription(Request $request): ?string
  {
    $method = $request->method();
    $route = $request->route()?->getName();

    if (!$route) {
      return null;
    }

    $actions = [
      'POST' => 'created',
      'PUT' => 'updated',
      'PATCH' => 'updated',
      'DELETE' => 'deleted',
      'GET' => 'viewed',
    ];

    $action = $actions[$method] ?? 'accessed';

    return "{$action} {$route}";
  }
}
