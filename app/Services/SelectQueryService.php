<?php
namespace App\Services;

use App\DTO\PostDTO;
use App\Models\Post;
use App\Common\ApiCommon;
use Illuminate\Support\Facades\DB;

class SelectQueryService{


    public function getPost(PostDTO $postDTO){
        try{
            $posts = Post::with('likes')->paginate($postDTO->getPerPage()); 
            foreach ($posts as $post) {
                $post->like_count = $post->getLikeCount(); 
            }

            // return ApiCommon::sendResponse($posts, 'Data berhasil didapat', 200);
            return ApiCommon::sendPaginatedResponse($posts, 'Data Berhasil Didapat !');

        }catch(\Exception $e){
            // DB::rollBack();
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }

    }


}