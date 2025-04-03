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
            $isBookmarkExist = Bookmark::where('user_id', $bookmarkDTO->getUser_id())->where('post_id', $bookmarkDTO->getPost_id())->first();
            if($isBookmarkExist === null){
                Db::commit();
                $bookmarkPost = new Bookmark();
                $bookmarkPost->post_id = $bookmarkDTO->getPost_id();
                $bookmarkPost->user_id = $bookmarkDTO->getUser_id();
                $bookmarkPost->save();
                $bookmarkPost['state'] = true;
                return ApiCommon::sendResponse($bookmarkPost, 'Berhasil Bookmark Post ', 200);
            }else{
                DB::commit();
                // $isLikeExists->deleted_at = Carbon::now();
                // $isLikeExists->save();
                $isBookmarkExist->delete();
                $isBookmarkExist['state'] = false;
                return ApiCommon::sendResponse($isBookmarkExist, 'Berhasil Remove Bookmark Post', 200);
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
