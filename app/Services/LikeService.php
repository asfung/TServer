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
            $isLikeExists = Like::where('user_id', $likeDTO->getUser_id())->first();
            if($isLikeExists === null){
                DB::commit();
                $likePost = new Like();
                $likePost->post_id = $likeDTO->getPost_id();
                $likePost->user_id = $likeDTO->getUser_id();
                $likePost->save();
                return ApiCommon::sendResponse($likePost, 'Berhasil Like Post ', 201);
            }else{
                DB::commit();
                $isLikeExists->deleted_at = Carbon::now();
                $isLikeExists->save();
                return ApiCommon::sendResponse($isLikeExists, 'Berhasil Remove Like Post', 201);
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
