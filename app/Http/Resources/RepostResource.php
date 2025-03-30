<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RepostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'original_post' => new PostResource($this->post),
            'user' => new UserResource($this->user),
            'created_at' => $this->created_at,
        ];
    }
}
