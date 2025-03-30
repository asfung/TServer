<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Tag;
use App\DTO\PostDTO;
use App\Models\Post;
use App\Models\User;
use App\Models\Quote;
use App\Common\ApiCommon;
use App\Events\PostNotificationEvent;
use App\Notifications\PostNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PostService
{


  public function newPost(PostDTO $postDTO){
    try {
      DB::beginTransaction();

      $for = $postDTO->getFor();

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


      // if yall want to make it more for, you can add more if statement here or make it with switch case
      if($for === 'quote'){
        $quote = new Quote();
        $quote->post_id = $newPost->id;
        $quote->user_id = $postDTO->getUser_id();
        $quote->save();
        return ApiCommon::sendResponse($newPost, 'Berhasil Membuat Quote', 201);
      }

      $user = User::find($newPost->user_id);
      $followers = $user->followers;
      foreach ($followers as $follow) {
        $followerUser = $follow->follower; // Get the User model
        $message = "User {$user->username} created a new post! " . $followerUser->id;
    
        // Dispatch WebSocket event
        event(new PostNotificationEvent($user, $followerUser->id, $message));
    
        // Store notification in the database
        $followerUser->notify(new PostNotification([
            'user' => $user,
            'message' => $message
        ]));
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

        $content = $postDTO->getContent();
        preg_match_all($hashtagPattern, $content, $hashtags);
        preg_match_all($mentionPattern, $content, $mentions);

        Tag::where('post_id', $postDTO->getPost_id())->delete();

        $editedPost = Post::where('id', $postDTO->getPost_id())->first();

        if ($editedPost->user_id !== $postDTO->getUser_id()) {
            return ApiCommon::sendResponse(null, 'user id is not matching on your token', 401, false);
        }

        $editedPost->content = $postDTO->getContent() ? $postDTO->getContent() : $editedPost->content;
        $editedPost->save();

        foreach ($hashtags[1] as $hashtag) {
            $tag = new Tag();
            $tag->post_id = $editedPost->id;
            $tag->tag_name = $hashtag;
            $tag->tag_formatted = '#' . $hashtag;
            $tag->type = 'hashtag';
            $tag->save();
        }

        foreach ($mentions[1] as $mention) {
            $tag = new Tag();
            $tag->post_id = $editedPost->id;
            $tag->tag_name = $mention;
            $tag->tag_formatted = '@' . $mention;
            $tag->type = 'mention';
            $tag->save();
        }

        $contentTag = new Tag();
        $contentTag->post_id = $editedPost->id;
        $contentTag->tag_name = 'content';
        $contentTag->tag_formatted = 'content';
        $contentTag->type = 'content';
        $contentTag->save();

        DB::commit();
        return ApiCommon::sendResponse($editedPost, 'Berhasil Mengedit Post', 201);
    } catch (\Exception $e) {
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
}
