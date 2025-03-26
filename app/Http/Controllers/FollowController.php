<?php

namespace App\Http\Controllers;

use App\Common\ApiCommon;
use App\DTO\FollowDTO;
use App\Services\FollowService;
use Illuminate\Http\Request;

class FollowController extends Controller{
    protected $followService;

    public function __construct(FollowService $followService){
        $this->followService = $followService;
    }

    public function toggleFollowCTLL(Request $request){
        try {
            $validated = $request->validate([
                'user_id_follower' => 'nullable', // the user who is following
                'user_id_followed' => 'required', // the user who is being followed
            ]);

            $followDTO = new FollowDTO();
            $followDTO->setUser_id_follower($validated['user_id_follower'] ?? ApiCommon::getUserId());
            $followDTO->setUser_id_followed($validated['user_id_followed']);

            return $this->followService->toggleFollow($followDTO);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function createCTLL(Request $request){
        try{
            $user_id_follower = ApiCommon::getUserId();
            $user_id_followed = $request->input('user_id_followed');

            $followDto = new FollowDTO();
            $followDto->setUser_id_follower($user_id_follower);
            $followDto->setUser_id_followed($user_id_followed);

            return $this->followService->create($followDto);

        }catch(\Exception $e){
            // ApiCommon::rollback($e->getMessage());
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteCTLL(Request $request){
        try{
            $user_id_follower = ApiCommon::getUserId();
            $user_id_followed = $request->input('user_id_followed');

            $followDto = new FollowDTO();
            $followDto->setUser_id_follower($user_id_follower);
            $followDto->setUser_id_followed($user_id_followed);

            return $this->followService->delete($followDto);

        }catch(\Exception $e){
            // ApiCommon::rollback($e->getMessage());
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }


}
