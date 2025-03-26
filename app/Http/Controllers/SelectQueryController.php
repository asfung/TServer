<?php

namespace App\Http\Controllers;

use App\Common\ApiCommon;
use App\DTO\PostDTO;
use App\Services\SelectQueryService;
use Illuminate\Http\Request;
use App\DTO\UserDTO;

class SelectQueryController extends Controller
{
    protected $selectQueryService;

    public function __construct(SelectQueryService $selectQueryService){
        $this->selectQueryService = $selectQueryService;
    }

    // POST
    public function getPostCTLL(Request $request){
        try {
            $request->validate([
                'post_id' => 'nullable|integer',
                'user_id' => 'nullable',
                'q' => 'nullable|string',
                'type' => 'nullable|string|in:bookmarks,reposts,replies,likes,post', 
                'per_page' => 'nullable|integer|min:1',
            ]);

            $post_id = $request->input('post_id');
            $type = $request->input('type');
            $perPage = $request->input('per_page', 10);
            $q = $request->input('q');
            $user_id = $request->has('user_id') ? $request->input('user_id') : ApiCommon::getUserId();

            $postDto = new PostDTO();
            $postDto->setPost_id($post_id);
            $postDto->setType($type);
            $postDto->setUser_id($user_id);
            $postDto->setPerPage($perPage);
            $postDto->setQ($q);

            return $this->selectQueryService->getPost($postDto);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getPostReplyCTLL(Request $request){
        try{

            $perPage = $request->input('per_page', 10); 
            $post_id = $request->input('post_id');

            $postDto = new PostDTO();
            $postDto->setPerPage($perPage);
            $postDto->setPost_id($post_id);

            return $this->selectQueryService->getPostReply($postDto);

        }catch(\Exception $e){
            // ApiCommon::rollback($e->getMessage());
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }


    // USER
    public function getUsernameCTLL(Request $request, $username){
        try{
            $username = $request->route('username'); 

            $userDto = new UserDTO();
            $userDto->setUsername($username);

            return $this->selectQueryService->getUsername($userDto);

        }catch(\Exception $e){
            // ApiCommon::rollback($e->getMessage());
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
