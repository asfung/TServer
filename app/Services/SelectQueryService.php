<?php

namespace App\Services;

use App\DTO\PostDTO;
use App\DTO\UserDTO;
use App\Models\Post;
use App\Models\User;
use App\Common\ApiCommon;
use Spatie\FlareClient\Api;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserProfileResource;

class SelectQueryService{

  // POST
  public function getPost(PostDTO $postDTO){
    try {
      if ($postDTO->getPost_id()) {
        // $posts = Post::where('id', $postDTO->getPost_id())
        //   ->whereNull('deleted_at')
        //   ->get();

        $postId = $postDTO->getPost_id();
        $posts = [];

        while ($postId) {
            $post = Post::where('id', $postId)
                ->whereNull('deleted_at')
                ->first();

            if ($post) {
                $posts[] = $post;
                $postId = $post->parent_id; 
            } else {
                break;
            }
        }

        if (empty($posts)) {
            return ApiCommon::sendResponse(null, 'No posts found!', 404, false);
        }

        return ApiCommon::sendResponse(PostResource::collection(collect($posts)), 'Data Berhasil Didapat !', 200, true, true);
      } else {
        $type = $postDTO->getType();
        $userId = $postDTO->getUser_id();

        $query = Post::query();

        if (!$type && !$postDTO->getQ()) {
          return ApiCommon::sendResponse(null, 'q params doesn\'t exists !', 400, false);
        }

        if ($postDTO->getQ()) {
          $query->where('content', 'like', '%' . $postDTO->getQ() . '%');
        }

        if ($type) {
          switch ($type) {
            case 'bookmarks':
              $query->whereHas('bookmarks', function ($q) use ($userId) {
                $q->where('user_id', $userId);
              });
              break;

            case 'reposts':
              $query->whereHas('reposts', function ($q) use ($userId) {
                $q->where('user_id', $userId);
              });
              break;

            case 'likes':
              $query->whereHas('likes', function ($q) use ($userId) {
                $q->where('user_id', $userId);
              });
              break;

            case 'following':
              $query->whereHas('user', function ($q) use ($userId) {
                $q->whereHas('followers', function ($q) use ($userId) {
                  $q->where('user_id_follower', $userId);
                });
              });
              // $query->whereHas('user.followers', function ($q) use ($userId) {
              //   $q->where('user_id_follower', $userId);
              // });
              break;
            
            case 'foryou':
              // return User::find($userId)->interactions();
              // $post = $query->select('id', 'content')->whereNull('deleted_at')->get();
              // return $post;

              // $postIds = [
              //   "151626773895839744",
              //   "162461095938752512",
              //   "162461870895136768",
              //   "162463491943301120",
              //   "151620593744084992",
              //   "151620743736590336",
              //   "151636214800187392",
              //   "151637575801503744",
              //   "152367054635139072"
              // ];
              // $postIds = User::find($userId)->interactions();

              // $query->whereIn('id', $postIds);

              break;

            case 'replies':
              $query->where('parent_id', '!=', null)
                ->where('user_id', $userId);
              break;

            case 'post': 
              $query->whereNull('parent_id')
                ->where('user_id', $userId);
              break;

            default:
              return ApiCommon::sendResponse(null, 'Invalid type provided!', 400, false);
          }
        }

        $posts = $query->whereNull('deleted_at')
          ->orderBy('created_at', 'desc')
          // ->whereNull('parent_id')
          ->paginate($postDTO->getPerPage());

        if ($posts->isEmpty()) {
          return ApiCommon::sendResponse(null, 'No posts found!', 404, false);
        }

        return ApiCommon::sendPaginatedResponse(PostResource::collection($posts), 'Data Berhasil Didapat !', 200);
      }
    } catch (\Exception $e) {
      return response()->json([
        'error' => $e->getMessage()
      ], 500);
    }
  }

  public function getPostReply(PostDTO $postDTO){
    try {
      $parentId = $postDTO->getPost_id();
      $isActivity = $postDTO->getActivity(); // true/false
      $parentPost = Post::find($parentId);

      if (!$parentPost) {
        return ApiCommon::sendResponse(null, 'Post not found!', 404, false);
      }

      $userIdParentPost = $parentPost->user_id;
      $activityPost = collect();
      $firstActivityPostId = null;

      $getActivity = function ($parentId) use (&$getActivity, $userIdParentPost, &$activityPost, &$firstActivityPostId) {
        $posts = Post::with(['likes', 'bookmarks'])
          ->where('parent_id', $parentId)
          ->where('user_id', $userIdParentPost)
          ->whereNull('deleted_at')
          ->orderBy('created_at', 'asc') 
          ->get();
        
        if ($posts->count() > 0) {
          $firstReply = $posts->first(); 
          if ($firstActivityPostId === null) {
            $firstActivityPostId = $firstReply->id;
          }
          $activityPost->push(new PostResource($firstReply));
          if ($posts->count() > 0) {
            $getActivity($firstReply->id);
          }
        }
      };

      if ($isActivity) {
        if ($activityPost->count() > 0) {
          $getActivity($parentId);
          return ApiCommon::sendResponse($activityPost, 'berhasil', 200);
        } else {
          return ApiCommon::sendResponse(null, 'not found', 404, false);
        }
      }

      $replies = Post::with(['likes', 'bookmarks'])
        ->where('parent_id', $parentId)
        ->whereNull('deleted_at')
        ->when($firstActivityPostId, function ($query) use ($firstActivityPostId, $userIdParentPost) {
          return $query->where(function ($q) use ($firstActivityPostId, $userIdParentPost) {
            $q->where('id', '!=', $firstActivityPostId) 
              ->orWhere('user_id', '!=', $userIdParentPost); 
          });
        })
        ->orderBy('created_at', 'desc')
        ->get();

      return ApiCommon::sendResponse(PostResource::collection($replies), 'success', 200);
    } catch (\Exception $e) {
      return ApiCommon::sendResponse(null, $e->getMessage(), 500, false);
    }
  }
  



  public function getPostBookmarks(PostDTO $postDTO){
    try {
      $post = Post::where('id', $postDTO->getPost_id())
        ->whereNull('deleted_at')
        ->firstOrFail();

      $bookmarks = $post->bookmarks()
        ->where('user_id', $postDTO->getUser_id())
        ->paginate($postDTO->getPerPage());

      if ($bookmarks->isEmpty()) {
        return ApiCommon::sendResponse(null, 'No bookmarks found!', 404, false);
      }

      return ApiCommon::sendPaginatedResponse($bookmarks, 'Data Berhasil Didapat !');
    } catch (\Exception $e) {
      return response()->json([
        'error' => $e->getMessage()
      ], 500);
    }
  }

  // USER
  public function getUsername(UserDTO $userDTO){
    try {
      $user = User::where('username', $userDTO->getUsername())
        ->first();

      if (!$user) {
        return ApiCommon::sendResponse(null, 'User not found!', 404, false);
      }

      return ApiCommon::sendResponse(new UserProfileResource($user), 'Data Berhasil Didapat !');
    } catch (\Exception $e) {
      return response()->json([
        'error' => $e->getMessage()
      ], 500);
    }
  }

}




// ABANDONED

// TREE ACTIVITY REPLIES
// public function getPostReply(PostDTO $postDTO){
//   try {
//     $parentId = $postDTO->getPost_id();
//     $isActivity = $postDTO->getActivity(); // true/false
//     $parentPost = Post::find($parentId);
//     $userIdParentPost = $parentPost->user_id;

//     $query = Post::with(['likes', 'bookmarks'])
//       ->where('parent_id', $parentId)
//       ->whereNull('deleted_at')
//       ->orderBy('created_at', 'desc');

//     $posts = $query->paginate($postDTO->getPerPage());
//     $getActivity = function ($parentId) use (&$getActivity, $userIdParentPost) {
//       return Post::with(['likes', 'bookmarks'])
//         ->where('parent_id', $parentId)
//         ->where('user_id', $userIdParentPost)
//         ->whereNull('deleted_at')
//         ->orderBy('created_at', 'desc')
//         ->get()
//         ->map(function ($post) use ($getActivity) {
//           $post->replies = $getActivity($post->id);
//           return $post;
//         });
//     };

//   $activityPost = $getActivity($parentId);


//     if ($posts->isEmpty()) {
//       return ApiCommon::sendResponse(null, 'No found!', 404, false);
//     }

//     if(!$isActivity){
//       return ApiCommon::sendPaginatedResponse(PostResource::collection($posts), 'Data Berhasil Didapat !');
//     }else{
//       // activity
//       return ApiCommon::sendResponse($activityPost, 'berhasil');
//     }
//   } catch (\Exception $e) {
//     return response()->json([
//       'error' => $e->getMessage()
//     ], 500);
//   }
// }
