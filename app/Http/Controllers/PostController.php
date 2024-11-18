<?php
namespace App\Http\Controllers;

use App\Common\ApiCommon;
use App\DTO\PostDTO;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController {

    protected $postService;

    public function __construct(PostService $postService){
        $this->postService = $postService;
    }

    public function newPostCTLL(Request $request){
        try{
            $request->validate([
                'content' => 'nullable',
                'parent_id' => 'nullable',
                'community_id' => 'nullable',
            ]);
            $content = $request->input('content');
            $parent_id = $request->input('parent_id');
            $community_id = $request->input('community_id');

            $postDTO = new PostDTO();
            $postDTO->setUser_id(ApiCommon::getUserId());
            $postDTO->setContent($content);
            $postDTO->setParent_id($parent_id);
            $postDTO->setCommunity_id($community_id);

            return $this->postService->newPost($postDTO);

            // DB::beginTransaction();

            // DB::commit();
            // $user = ApiCommon::getUser();
            // $newPost = new Post();
            // $newPost->user_id = $user->id;
            // $newPost->content = $content;
            // $newPost->parent_id = $parent_id;
            // $newPost->community_id = $community_id;

            // $newPost->save();

            // return ApiCommon::sendResponse($newPost, 'Berhasil Membuat Post', 201);


        }catch(\Exception $e){
            // ApiCommon::rollback($e->getMessage());
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}