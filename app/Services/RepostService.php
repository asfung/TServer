<?php

namespace App\Services;

use App\DTO\PostDTO;
use App\Models\Repost;
use App\Common\ApiCommon;
use Illuminate\Support\Facades\DB;

class RepostService{

  public function store_and_scratchOut(PostDTO $postDTO){
    try {
      DB::beginTransaction();
      $isRepostExists = Repost::where('user_id', $postDTO->getUser_id())->where('post_id', $postDTO->getPost_id())->first();
      if ($isRepostExists === null) {
        DB::commit();
        $repostPost = new Repost();
        $repostPost->post_id = $postDTO->getPost_id();
        $repostPost->user_id = $postDTO->getUser_id();
        $repostPost->save();
        $repostPost['state'] = true;
        return ApiCommon::sendResponse($repostPost, 'Berhasil Repost Post ', 200);
      } else {
        DB::commit();
        // $isRepostExists->deleted_at = Carbon::now();
        // $isRepostExists->save();
        $isRepostExists->delete();
        $isRepostExists['state'] = false;
        return ApiCommon::sendResponse($isRepostExists, 'Berhasil Remove Repost Post', 200);
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
