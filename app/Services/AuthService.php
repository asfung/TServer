<?php

namespace App\Services;

use App\Common\ApiCommon;
use App\DTO\UserDTO;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\AuthResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;

use function PHPUnit\Framework\isEmpty;

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
          'user' => new AuthResource($user),
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
          'user' => new AuthResource($user),
          'authorization' => [
            'token' => $newToken,
            'type' => 'bearer',
          ]
        ]);
      } catch (TokenExpiredException $e) {
        return response()->json([
          'status' => 'error',
          'key' => 'unable-to-refresh',
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
        'user' => new AuthResource($user),
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
    $userRoleId = DB::table('roles')->where('name', 'User')->value('id');
    try {
      $user = User::create([
        'display_name' => $userDTO->getDisplay_name(),
        'username' => $userDTO->getUsername(),
        'email' => $userDTO->getEmail(),
        'password' => Hash::make($userDTO->getPassword()),
        'role_id' => $userRoleId,
      ]);

      $token = Auth::login($user);
      return response()->json([
        'status' => 'success',
        'message' => 'User created successfully',
        'user' => new AuthResource($user),
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

  public function changePassword(UserDTO $userDTO){
    try{
      DB::beginTransaction();
      $user_id = $userDTO->getUser_id();
      $old_password = $userDTO->getOld_password();
      $new_password = $userDTO->getNew_password();

      $userExists = User::find($user_id);
      if(is_null($userExists)){
        return ApiCommon::sendResponse(null, 'user not found', 404, false);
      }
      if(!Hash::check($old_password, $userExists->password)){
        return ApiCommon::sendResponse(null, 'old password not match', 422, false);
      }
      DB::commit();
      $userExists->password = Hash::make($new_password);
      $userExists->save();

      return ApiCommon::sendResponse(null, 'Berhasil Ganti Password', 200);
    } catch (\Exception $e) {
      return response()->json([
        'error' => $e->getMessage()
      ], 500);
    }
  }

}
