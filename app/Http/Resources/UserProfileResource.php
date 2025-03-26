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
        return [
            'id' => $this->id,
            'display_name' => $this->display_name,
            'followers_count' => $this->getFollowersCountAttribute(),
            'following_count' => $this->getFollowingCountAttribute(),
            'username' => $this->username,
            'bio' => $this->bio,
            'address' => $this->address,
            'profile_image' => $this->profile_image ? new MediaResource($this->media) : null,
        ];
    }
}
