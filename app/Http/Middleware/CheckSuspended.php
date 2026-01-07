<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSuspended
{
  /**
   * Handle an incoming request.
   */
  public function handle(Request $request, Closure $next): Response
  {
    if (Auth::check() && Auth::user()->is_suspended) {
      $suspensionReason = Auth::user()->suspension_reason;
      Auth::logout();

      return redirect()->route('login')
        ->with('error', 'Akun Anda telah ditangguhkan: ' . $suspensionReason);
    }

    return $next($request);
  }
}
