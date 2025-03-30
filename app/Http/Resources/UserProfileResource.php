<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $isMe = auth()->check() && auth()->id() === $this->id;
        return [
            'id' => $this->id,
            'display_name' => $this->display_name,
            'username' => $this->username,
            'badge' => $this->badge,
            'bio' => $this->bio,
            'address' => $this->address,
            'followers_count' => $this->getFollowersCountAttribute(),
            'following_count' => $this->getFollowingCountAttribute(),
            // 'followers' => $this->followers,
            'followed' => $this->getIsFollowedAttribute(),
            'profile_image' => $this->profile_image ? new MediaResource($this->media) : null,
            'is_me' => $isMe,
        ];
    }
}
