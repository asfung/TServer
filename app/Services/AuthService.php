<?php

namespace App\Services;

use App\DTO\UserDTO;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;

class AuthService
{

  public function checkToken()
  {
    try {
      try {
        $user = JWTAuth::parseToken()->authenticate();
        return response()->json([
          'status' => 'success',
          'message' => 'Token is valid.',
          'user' => $user
        ]);
      } catch (TokenExpiredException $e) {
        return response()->json([
          'status' => 'error',
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
    } catch (\Exception $e) {
      return response()->json([
        'error' => $e->getMessage()
      ], 500);
    }
  }

  public function refreshToken()
  {
    try {
      try {
        $newToken = JWTAuth::parseToken()->refresh();
        $user = JWTAuth::setToken($newToken)->toUser();
        return response()->json([
          'status' => 'success',
          'user' => $user,
          'authorization' => [
            'token' => $newToken,
            'type' => 'bearer',
          ]
        ]);
      } catch (TokenExpiredException $e) {
        return response()->json([
          'status' => 'error',
          'message' => 'Token has expired and cannot be refreshed.',
        ], 401);
      } catch (TokenInvalidException $e) {
        return response()->json([
          'status' => 'error',
          'message' => 'Token is invalid.',
        ], 401);
      } catch (JWTException $e) {
        return response()->json([
          'status' => 'error',
          'message' => 'Token could not be refreshed.',
        ], 401);
      }
    } catch (\Exception $e) {
      return response()->json([
        'error' => $e->getMessage()
      ], 500);
    }
  }


  public function login($credentials, $email, $password)
  {
    try {
      $user = User::where('email', $email)->first();
      if (!$user) {
        return response()->json([
          'status' => 'error',
          'message' => 'Email not found',
        ], 404);
      }

      if (!Hash::check($password, $user->password)) {
        return response()->json([
          'status' => 'error',
          'message' => 'Incorrect password',
        ], 401);
      }

      $token = Auth::attempt($credentials);
      if (!$token) {
        return response()->json([
          'status' => 'error',
          'message' => 'Unauthorized',
        ], 401);
      }

      $user = Auth::user();
      return response()->json([
        'status' => 'success',
        'user' => $user,
        'authorization' => [
          'token' => $token,
          'type' => 'bearer',
        ]
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'error' => $e->getMessage()
      ], 500);
    }
  }

  public function register(UserDTO $userDTO)
  {
    try {
      $user = User::create([
        'display_name' => $userDTO->getDisplay_name(),
        'username' => $userDTO->getUsername(),
        'email' => $userDTO->getEmail(),
        'password' => Hash::make($userDTO->getPassword()),
        'role_id' => 5,
      ]);

      $token = Auth::login($user);
      return response()->json([
        'status' => 'success',
        'message' => 'User created successfully',
        'user' => $user,
        'authorization' => [
          'token' => $token,
          'type' => 'bearer',
        ]
      ]);

    } catch (\Exception $e) {
      return response()->json([
        'error' => $e->getMessage()
      ], 500);
    }
  }

}
