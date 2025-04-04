<?php

namespace App\Services;

use App\Common\ApiCommon;
use App\DTO\FollowDTO;
use App\Models\Follow;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FollowService {

    public function toggleFollow(FollowDTO $followDTO){
        try {
            DB::beginTransaction();
            $user_id_follower = $followDTO->getUser_id_follower();
            $user_id_followed = $followDTO->getUser_id_followed();

            if ($user_id_follower === $user_id_followed) {
                return ApiCommon::sendResponse(null, 'you cannot follow yourself, are you lonely bruh?', 400, false);
            }

            $userFollow = Follow::where('user_id_follower', $user_id_follower)
                ->where('user_id_followed', $user_id_followed)
                ->first();

            if ($userFollow) {
                if($userFollow->deleted_at !== null){
                    $userFollow->deleted_at = null;
                    $userFollow->save();
                    DB::commit();
                    $userFollow['state'] = true;
                    return ApiCommon::sendResponse($userFollow, 'followed', 200);
                }else{
                    $userFollow->deleted_at = Carbon::now();
                    $userFollow->save();
                    DB::commit();
                    $userFollow['state'] = false;
                    return ApiCommon::sendResponse($userFollow, 'unfollowed', 200);
                }
            } else {
                $follow = new Follow();
                $follow->user_id_follower = $user_id_follower;
                $follow->user_id_followed = $user_id_followed;
                $follow->save();
                DB::commit();
                $follow['state'] = true;
                return ApiCommon::sendResponse($follow, 'new followed', 201);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

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
