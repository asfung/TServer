<?php

namespace App\Http\Middleware;

use Closure;
use App\Common\ApiCommon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;

class AccessControl
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {

    $currentRoute = $request->route()->getName();

    // if ($currentRoute === 'auth.refresh_token' || $currentRoute === 'auth.check_token') {
    if ($currentRoute === 'auth.refresh_token') {
      return $next($request);
    }

    try {
      JWTAuth::parseToken()->authenticate();
    } catch (TokenExpiredException $e) {
      return response()->json([
        'status' => 'error',
        'key' => 'refresh-token',
        'message' => 'Token has expired.',
      ], 401);
    } catch (TokenInvalidException $e) {
      return response()->json([
        'status' => 'error',
        'message' => 'Token is invalid.',
      ], 401);
    } catch (JWTException $e) {
      return response()->json([
        'status' => 'error',
        'message' => 'Token not provided.',
      ], 401);
    }
  
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
