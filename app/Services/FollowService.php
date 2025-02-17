<?php

namespace App\Services;

use App\Common\ApiCommon;
use App\DTO\FollowDTO;
use App\Models\Follow;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FollowService {

    public function create(FollowDTO $followDTO){
        try{
            DB::beginTransaction();
            $user_id_follower = $followDTO->getUser_id_follower();
            $user_id_followed = $followDTO->getUser_id_followed();

            $userFollow = Follow::where('user_id_follower', $user_id_follower)->where('user_id_followed', $user_id_followed)->first();
            if($userFollow){
                return $this->delete($followDTO);
            }else{
                $follow = new Follow();
                $follow->user_id_follower = $user_id_follower;
                $follow->user_id_followed = $user_id_followed;
                $follow->save();
                return ApiCommon::sendResponse($follow, 'Followwed', 201);
            }


        }catch(\Exception $e){
            // ApiCommon::rollback($e->getMessage());
            DB::rollBack();
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function delete(FollowDTO $followDTO){
        try{
            DB::beginTransaction();
            $user_id_follower = $followDTO->getUser_id_follower();
            $user_id_followed = $followDTO->getUser_id_followed();

            $userFollow = Follow::where('user_id_follower', $user_id_follower)->where('user_id_followed', $user_id_followed)->first();
            if($userFollow){
                $userFollow->deleted_at = Carbon::now();
                return ApiCommon::sendResponse(null, 'Unfollowed', 201);
            }

        }catch(\Exception $e){
            // ApiCommon::rollback($e->getMessage());
            DB::rollBack();
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
