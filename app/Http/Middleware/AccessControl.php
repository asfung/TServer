<?php

namespace App\Http\Middleware;

use App\Common\ApiCommon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AccessControl
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    $user = auth()->user();
    $role = $user->role; // assuming user has role_id

    $currentRoute = $request->route()->getName();

    $hasAccess = $role->permissions()
      ->where('endpoint', $currentRoute)
      ->exists();

    if (!$hasAccess) {
      return response()->json(['error' => 'You Don\'t have acccess'], 403);
    }

    return $next($request);
  }
}
