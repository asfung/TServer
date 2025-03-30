<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReplyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $user = $request->user();
        return [
            'id' => $this->id,
            'content' => $this->content,
            'user' => new UserResource($this->user),
            'is_bookmarked' => auth()->check() ? $this->isBookmarked(auth()->user()) : false,
            'is_reposted' => auth()->check() ? $this->reposted(auth()->user()) : false,
            'is_liked' => $user ? $this->likedBy($user) : false, 
            'like_count' => $this->getLikeCount(),
            'repost_count' => $this->getRepostCount(),
            'replies_count' => $this->replies->count(),
            'media' => MediaResource::collection($this->media->whereNull('deleted_at')),
            // 'parent_post' => new PostResource($this->parent),
            'created_at' => $this->created_at,
        ];
    }
}
