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
