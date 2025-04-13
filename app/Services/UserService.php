<?php

namespace App\Services;

use App\DTO\UserDTO;
use App\Models\User;
use App\Common\ApiCommon;
use Illuminate\Support\Facades\DB;

class UserService {

  // unused
  public function updateNonBinaryUser(UserDTO $userDTO){
    try {
      DB::beginTransaction();
      $isUserExist = User::where('id', $userDTO->getUser_id())->first();
      if ($isUserExist !== null) {
        Db::commit();
        $user = new User();
        $user->display_name = $userDTO->getDisplay_name();
        $user->bio = $userDTO->getBio();
        $user->save();
        return ApiCommon::sendResponse($isUserExist, 'Berhasil Merubah User ', 201);
      } else {
        return ApiCommon::sendResponse(null, 'User Doesnt Exist', 404);
      }
    } catch (\Exception $e) {
      // ApiCommon::rollback($e->getMessage());
      DB::rollBack();
      return response()->json([
        'error' => $e->getMessage()
      ], 500);
    }
  }
}
