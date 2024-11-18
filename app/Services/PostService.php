<?php
namespace App\Services;

use App\DTO\PostDTO;
use App\Models\Post;
use App\Common\ApiCommon;
use Illuminate\Support\Facades\DB;

class PostService {


    public function newPost(PostDTO $postDTO){
        try{
            DB::beginTransaction();

            // TODO: make a regex for link, hashtag, mentions and then new data for posts children table

            DB::commit();
            $newPost = new Post();
            $newPost->user_id = $postDTO->getUser_id();
            $newPost->content = $postDTO->getContent();
            $newPost->parent_id = $postDTO->getParent_id();
            $newPost->community_id = $postDTO->getCommunity_id();

            $newPost->save();

            return ApiCommon::sendResponse($newPost, 'Berhasil Membuat Post', 201);
        }catch(\Exception $e){
            // ApiCommon::rollback($e->getMessage());
            DB::rollBack();
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }


}


