<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Common\ApiCommon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
  public function updateUser(Request $request, $id)
  {
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

      return ApiCommon::sendResponse($user, 'User updated successfully', 200);
    } catch (\Exception $e) {
      DB::rollBack();
      return response()->json(['error' => $e->getMessage()], 500);
    }
  }
}
