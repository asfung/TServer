<?php

namespace App\Services;

use App\Models\Tag;
use App\DTO\PostDTO;
use App\Models\Post;
use App\Models\Quote;
use App\Common\ApiCommon;
use Illuminate\Support\Facades\DB;

class QouteService{

    // DEPRECATED
    public function newQuote(PostDTO $postDTO){
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

          $newQuote = new Quote();
          $newQuote->post_id = $newPost->id;
          $newQuote->save();
    
    
          return ApiCommon::sendResponse($newQuote, 'Berhasil Membuat Quote', 201);
        } catch (\Exception $e) {
          // ApiCommon::rollback($e->getMessage());
          DB::rollBack();
          return response()->json([
            'error' => $e->getMessage()
          ], 500);
        }
      }
}
