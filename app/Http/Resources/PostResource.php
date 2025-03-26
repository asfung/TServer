<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = $request->user();
        return [
            'id' => $this->id,
            // 'user_id' => $this->user_id,
            'user' => new UserResource($this->user),
            'content' => $this->content,
            'parent_id' => $this->parent_id,
            'community_id' => $this->community_id,
            'is_bookmarked' => auth()->check() ? $this->isBookmarked(auth()->user()) : false,
            'is_reposted' => auth()->check() ? $this->reposted(auth()->user()) : false,
            'is_liked' => $user ? $this->likedBy($user) : false, 
            'like_count' => $this->getLikeCount(),
            'repost_count' => $this->getRepostCount(),
            'replies_count' => $this->replies->count(),
            'likes' => $this->likes,
            'media' => MediaResource::collection($this->media),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
