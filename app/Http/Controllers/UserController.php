<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Common\ApiCommon;
use App\Http\Resources\AuthResource;
use App\Http\Resources\UserAdminResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller {

  public function updateUserCTLL(Request $request) {
    try {
      DB::beginTransaction();

      $userId = ApiCommon::getUserId();
      $user = User::find($userId);
      if (!$user) {
        return ApiCommon::sendResponse(null, 'User does not exist', 404);
      }

      $validatedData = $request->validate([
        'display_name' => 'nullable|string|max:150',
        'bio' => 'nullable|string',
        'address' => 'nullable|string',
      ]);

      $user->update($validatedData);
      DB::commit();

      return ApiCommon::sendResponse(new AuthResource($user), 'User updated successfully', 200);
    } catch (\Exception $e) {
      DB::rollBack();
      return response()->json(['error' => $e->getMessage()], 500);
    }
  }

  // admin
  public function index(Request $request) {
    $perPage = $request->get('per_page', 10);
    $search = $request->get('search');

    $query = User::with('role')->orderBy('created_at', 'desc');

    if ($search) {
      $query->where(function ($q) use ($search) {
        $q->where('display_name', 'LIKE', "%$search%")
          ->orWhere('username', 'LIKE', "%$search%")
          ->orWhere('email', 'LIKE', "%$search%");
      });
    }

    $users = $query->paginate($perPage);

    return ApiCommon::sendPaginatedResponse(UserAdminResource::collection($users), "User list fetched successfully", 200);
  }
  

  public function store(Request $request) {
    try {
      $data = $request->validate([
        'display_name' => 'required|string|max:255',
        'username' => 'required|string|max:255|unique:users|not_regex:/\s/',
        'email' => 'required|email|unique:users',
        'address' => 'nullable|string',
        'bio' => 'nullable|string',
        'badge' => 'nullable|string',
        'banned' => 'nullable|boolean',
        'password' => 'required|string|min:8',
        'role_id' => 'required|exists:roles,id',
      ]);

      $data['password'] = Hash::make($data['password']);

      $user = User::create($data);

      return ApiCommon::sendResponse(new UserAdminResource($user), "User created successfully", 201);
    } catch (\Throwable $e) {
      return ApiCommon::throw($e, $e->getMessage());
    }
  }

  public function show($id) {
    try {
      $user = User::findOrFail($id);
      return ApiCommon::sendResponse(new UserAdminResource($user), "User fetched successfully", 200);
    } catch (\Throwable $e) {
      return ApiCommon::throw($e, "User not found");
    }
  }

  public function update(Request $request, $id) {
    try {
      $user = User::findOrFail($id);

      $data = $request->validate([
        'display_name' => 'sometimes|required|string|max:255',
        'username' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('users')->ignore($user->id), 'not_regex:/\s/'],
        'email' => ['sometimes', 'required', 'email', Rule::unique('users')->ignore($user->id)],
        'address' => 'nullable|string',
        'bio' => 'nullable|string',
        'badge' => 'nullable|string',
        'banned' => 'nullable|boolean',
        'password' => 'nullable|string|min:8',
        'role_id' => 'exists:roles,id',
      ]);

      // if (isset($data['password'])) {
      //   $data['password'] = Hash::make($data['password']);
      // }
      if (!empty(trim($data['password'] ?? ''))) {
        $data['password'] = Hash::make($data['password']);
      } else {
        unset($data['password']);
      }


      $user->update($data);

      return ApiCommon::sendResponse(new UserAdminResource($user), "User updated successfully", 200);
    } catch (\Throwable $e) {
      return ApiCommon::throw($e, $e->getMessage());
    }
  }

  public function destroy($id) {
    try {
      $user = User::findOrFail($id);
      $user->delete();

      return ApiCommon::sendResponse(null, "User deleted successfully", 200);
    } catch (\Throwable $e) {
      return ApiCommon::throw($e, "Failed to delete user");
    }
  }
}
