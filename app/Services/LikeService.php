<?php
namespace App\Services;

use App\DTO\LikeDTO;
use App\Models\Like;
use App\Common\ApiCommon;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class LikeService {


    public function store_and_scratchOut(LikeDTO $likeDTO){
        try{
            DB::beginTransaction();
            $isLikeExist = Like::where('user_id', $likeDTO->getUser_id())->where('post_id', $likeDTO->getPost_id())->first();
            if($isLikeExist === null){
                DB::commit();
                $likePost = new Like();
                $likePost->post_id = $likeDTO->getPost_id();
                $likePost->user_id = $likeDTO->getUser_id();
                $likePost->save();
                $likePost['state'] = true;
                return ApiCommon::sendResponse($likePost, 'Berhasil Like Post ', 200);
            }else{
                DB::commit();
                // $isLikeExists->deleted_at = Carbon::now();
                // $isLikeExists->save();
                $isLikeExist->delete();
                $isLikeExist['state'] = false;
                return ApiCommon::sendResponse($isLikeExist, 'Berhasil Remove Like Post', 200);
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
