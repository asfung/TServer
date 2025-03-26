<?php

namespace App\Services;

use App\Models\Tag;
use App\DTO\PostDTO;
use App\Models\Post;
use App\Common\ApiCommon;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PostService
{


  public function newPost(PostDTO $postDTO){
    try {
      DB::beginTransaction();
      $hashtagPattern = '/#(\w+)/';
      $mentionPattern = '/@(\w+)/';


      // TODO: make a regex for link, hashtag, mentions and then new data for posts children table
      $content = $postDTO->getContent();
      preg_match_all($hashtagPattern, $content, $hashtags);
      preg_match_all($mentionPattern, $content, $mentions);

      DB::commit();
      $newPost = new Post();
      $newPost->user_id = $postDTO->getUser_id();
      $newPost->content = $postDTO->getContent();
      $newPost->parent_id = $postDTO->getParent_id();
      $newPost->community_id = $postDTO->getCommunity_id();

      $newPost->save();

      // hashtags
      foreach ($hashtags[1] as $hashtag) {
        $tag = new Tag();
        $tag->post_id = $newPost->id;
        $tag->tag_name = $hashtag;
        $tag->tag_formatted = '#' . $hashtag;
        $tag->type = 'hashtag';
        $tag->save();
      }

      // mentions
      foreach ($mentions[1] as $mention) {
        $tag = new Tag();
        $tag->post_id = $newPost->id;
        $tag->tag_name = $mention;
        $tag->tag_formatted = '@' . $mention;
        $tag->type = 'mention';
        $tag->save();
      }


      return ApiCommon::sendResponse($newPost, 'Berhasil Membuat Post', 201);
    } catch (\Exception $e) {
      // ApiCommon::rollback($e->getMessage());
      DB::rollBack();
      return response()->json([
        'error' => $e->getMessage()
      ], 500);
    }
  }

  public function updatePost(PostDTO $postDTO){
    try {
      DB::beginTransaction();
      $hashtagPattern = '/#(\w+)/';
      $mentionPattern = '/@(\w+)/';


      // TODO: removing the existing of raw data from tag_table
      $content = $postDTO->getContent();
      preg_match_all($hashtagPattern, $content, $hashtags);
      preg_match_all($mentionPattern, $content, $mentions);

      $existingTags = Tag::where('post_id', $postDTO->getPost_id())->get();
      foreach ($existingTags as $tag) {
        $existingTags->deleted_at = Carbon::now();
        DB::commit();
      }


      // $newPost = new Post();
      // $newPost->user_id = $postDTO->getUser_id();
      // $newPost->content = $postDTO->getContent();
      // $newPost->parent_id = $postDTO->getParent_id();
      // $newPost->community_id = $postDTO->getCommunity_id();
      $editedPost = Post::where('id', $postDTO->getPost_id())->get()->first();

      if ($editedPost->user_id !== $postDTO->getUser_id()) {
        return ApiCommon::sendResponse(null, 'user id is not matching on your token', 401, false);
      }

      $editedPost->content = $postDTO->getContent() ? $postDTO->getContent() : $editedPost->content;
      $editedPost->save();
      DB::commit();

      // hashtags
      foreach ($hashtags[1] as $hashtag) {
        $tag = new Tag();
        $tag->post_id = $editedPost->id;
        $tag->tag_name = $hashtag;
        $tag->tag_formatted = '#' . $hashtag;
        $tag->type = 'hashtag';
        $tag->save();
      }

      // mentions
      foreach ($mentions[1] as $mention) {
        $tag = new Tag();
        $tag->post_id = $editedPost->id;
        $tag->tag_name = $mention;
        $tag->tag_formatted = '@' . $mention;
        $tag->type = 'mention';
        $tag->save();
      }


      return ApiCommon::sendResponse($editedPost, 'Berhasil Mnegedit Post', 201);
    } catch (\Exception $e) {
      // ApiCommon::rollback($e->getMessage());
      DB::rollBack();
      return response()->json([
        'error' => $e->getMessage()
      ], 500);
    }
  }

  public function deletePost(PostDTO $postDTO){
    try {
      DB::beginTransaction();

      // $deletedPost = Post::where('id', $postDTO->getPost_id())->get()->first();
      $deletedPost = Post::find($postDTO->getPost_id());

      if ($deletedPost->user_id !== $postDTO->getUser_id()) {
        return ApiCommon::sendResponse(null, 'user id is not matching on your token', 401, false);
      }

      $deletedPost->deleted_at = Carbon::now();
      $deletedPost->save();
      DB::commit();

      return ApiCommon::sendResponse($deletedPost, 'Berhasil Menghapus Data', 200);
    } catch (\Exception $e) {
      // ApiCommon::rollback($e->getMessage());
      DB::rollBack();
      return response()->json([
        'error' => $e->getMessage()
      ], 500);
    }
  }

  // QUERY
  public function getPost(PostDTO $postDTO){
    try{

      return ApiCommon::sendResponse(['dsadas' => 'dasds'], 'dsads', 200);

    } catch (\Exception $e) {
      return response()->json([
        'error' => $e->getMessage()
      ], 500);
    }
  }

}
