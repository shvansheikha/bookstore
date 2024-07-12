<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'user_id' => $this->user_id,
            'author_id' => $this->author_id,
            'author' => AuthorResource::make($this->whenLoaded('author')),
        ];
    }
}
