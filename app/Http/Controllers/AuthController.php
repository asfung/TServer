<?php

namespace App\Http\Controllers;

use App\Common\ApiCommon;
use App\DTO\UserDTO;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller{

  private $authService;

  public function __construct(AuthService $authService){
    $this->authService = $authService;
  }

  public function loginCTLL(Request $request){
    $request->validate([
      'email' => 'required|string|email',
      'password' => 'required|string',
    ]);
    $credentials = $request->only('email', 'password');
    $email = $request->input('email');
    $password = $request->input('password');
    return $this->authService->login($credentials, $email, $password);
  }

  public function registerCTLL(Request $request){
    try {
      $request->validate([
        'display_name' => 'required|string|max:255',
        'username' => 'required|string|max:255|unique:users|not_regex:/\s/',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6',
      ]);

      $userDTO = new UserDTO();
      $userDTO->setDisplay_name($request->input('display_name'));
      $userDTO->setUsername($request->input('username'));
      $userDTO->setEmail($request->input('email'));
      $userDTO->setPassword($request->input('password'));
      return $this->authService->register($userDTO);
    } catch (\Exception $e) {
      return response()->json([
        'error' => $e->getMessage()
      ], 500);
    }
  }

  public function changePasswordCTLL(Request $request){
    try {
      $request->validate([
        'old_password' => 'required|string|max:255',
        'new_password' => 'required|string|max:255',
      ]);

      $userDTO = new UserDTO();
      $userDTO->setOld_password($request->input('old_password'));
      $userDTO->setNew_password($request->input('new_password'));
      $userDTO->setUser_id(ApiCommon::getUserId());

      return $this->authService->changePassword($userDTO);
    } catch (\Exception $e) {
      return response()->json([
        'error' => $e->getMessage()
      ], 500);
    }
  }


  public function logoutCTLL(){
    Auth::logout();
    return response()->json([
      'status' => 'success',
      'message' => 'Successfully logged out',
    ]);
  }

  public function refreshCTLL(){
    return $this->authService->refreshToken();
  }

  public function checkTokenCTLL(Request $request){
    try {
      return $this->authService->checkToken();
    } catch (\Exception $e) {
      return response()->json([
        'error' => $e->getMessage()
      ], 500);
    }
  }
}
