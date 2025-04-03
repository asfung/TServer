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
            'followers_count_formatted' => $this->username === 'Paung' ? $this->formatNumber(9000_000_000) :  $this->formatNumber($this->getFollowersCountAttribute()),
            'following_count_formatted' => $this->username === 'Paung' ?  $this->formatNumber(-9999_9999) :  $this->formatNumber($this->getFollowingCountAttribute()),
            'post_count_formatted' => $this->username === 'Paung' ?  $this->formatNumber(69_000_000_000) :  $this->formatNumber($this->getPostCount()),

            // 'followers_count' => $this->getFollowersCountAttribute(),
            // 'following_count' => $this->getFollowingCountAttribute(),
            // 'post_count' => $this->getPostCount(),
            // TYPICAL
            'followers_count' => $this->username === 'Paung' ? 9000_000_000 : $this->getFollowersCountAttribute(),
            'following_count' => $this->username === 'Paung' ? -9999_9999 : $this->getFollowingCountAttribute(),
            'post_count' => $this->username === 'Paung' ? 69_000_000_000 : $this->getPostCount(),
            // 'followers' => $this->followers,
            'followed' => $this->getIsFollowedAttribute(),
            'profile_image' => $this->profile_image ? new MediaResource($this->media) : null,
            'is_me' => $isMe,
            'created_at' => $this->created_at,
        ];
    }

    private function formatNumber($number): string|int{
        if ($number >= 1000) {
            return sprintf("%.1e", $number); 
        }
        return $number; 
    }
}
