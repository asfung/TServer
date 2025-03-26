<?php

namespace App\Services;

use App\DTO\BookmarkDTO;
use App\Models\Bookmark;
use App\Common\ApiCommon;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BookmarkService {


    public function store_and_scratchOut(BookmarkDTO $bookmarkDTO){
        try{
            DB::beginTransaction();
            $isLikeExists = Bookmark::where('user_id', $bookmarkDTO->getUser_id())->first();
            if($isLikeExists === null){
                Db::commit();
                $bookmarkPost = new Bookmark();
                $bookmarkPost->post_id = $bookmarkDTO->getPost_id();
                $bookmarkPost->user_id = $bookmarkDTO->getUser_id();
                $bookmarkPost->save();
                return ApiCommon::sendResponse($bookmarkPost, 'Berhasil Bookmark Post ', 201);
            }else{
                DB::commit();
                // $isLikeExists->deleted_at = Carbon::now();
                // $isLikeExists->save();
                $isLikeExists->delete();
                return ApiCommon::sendResponse($isLikeExists, 'Berhasil Remove Bookmark Post', 201);
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
