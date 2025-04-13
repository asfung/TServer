<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserAdminResource extends JsonResource
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
            'profile_image' => $this->media,
            'display_name' => $this->display_name,
            'username' => $this->username,
            'email' => $this->email,
            'address' => $this->address,
            'bio' => $this->bio,
            'badge' => $this->badge,
            'banned' => $this->banned,
            'email_verified_at' => $this->email_verified_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'role_id' => $this->role_id,
            'role' => $this->role
        ];

    }
}
