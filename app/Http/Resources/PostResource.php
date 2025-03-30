<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\QuoteResource;
use App\Http\Resources\RepostResource;
use App\Http\Resources\ReplyResource;

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
            'user' => new UserResource($this->user),
            'content' => $this->content,
            'parent_id' => $this->parent_id,
            'community_id' => $this->community_id,
            'bookmarked' => auth()->check() ? $this->isBookmarked(auth()->user()) : false,
            'reposted' => auth()->check() ? $this->reposted(auth()->user()) : false,
            'liked' => $user ? $this->likedBy($user) : false, 
            'like_count' => $this->getLikeCount(),
            'repost_count' => $this->getRepostCount(),
            'replies_count' => $this->replies->count(),
            'likes' => $this->likes,
            'media' => MediaResource::collection($this->media->whereNull('deleted_at')),
            '__typename' => $this->quotes->isNotEmpty() ? 'quote' : ($this->parent_id ? ($this->reposts->isNotEmpty() ? 'repost' : 'reply') : 'post'),
            // '__typename' => $this->quotes->isNotEmpty()
            //     ? 'quote'
            //     : ($this->parent_id
            //         ? ($this->reposts->isNotEmpty() ? 'repost' : 'reply')
            //         : 'post'
            //     ),
            // 'quote' => $this->quotes->isNotEmpty() ? new QuoteResource($this->quotes->first()->post) : null,
            'quote' => $this->quotes->isNotEmpty() ? new QuoteResource($this->parent) : null,
            // 'repost' => $this->reposts->isNotEmpty() ? new RepostResource($this->reposts->first()) : null,
            // 'repost' => $this->reposts->isNotEmpty() ? $this->reposts : null,
            'reply' => $this->parent_id ? new ReplyResource($this->parent) : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
