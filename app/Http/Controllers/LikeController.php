<?php

namespace App\Http\Controllers;

use App\Common\ApiCommon;
use App\DTO\LikeDTO;
use App\Services\LikeService;
use Illuminate\Http\Request;

class LikeController extends Controller{
    protected $likeService;

    public function __construct(LikeService $likeService){
        $this->likeService = $likeService;
    }

    public function likeToCTLL(Request $request){
        try{
            $request->validate([
                'post_id' => 'required',
            ]);
            $post_id = $request->input('post_id');

            $likeDTO = new LikeDTO();
            $likeDTO->setPost_id($post_id);
            $likeDTO->setUser_id(ApiCommon::getUserId());
            return $this->likeService->store_and_scratchOut($likeDTO);

        }catch(\Exception $e){
            // ApiCommon::rollback($e->getMessage());
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }


}
