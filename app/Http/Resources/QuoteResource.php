<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuoteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'user' => new UserResource($this->user),
            'media' => MediaResource::collection($this->media->whereNull('deleted_at')),
            'created_at' => $this->created_at,
        ];
    }
}
