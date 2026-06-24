<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'couple_id' => $this->couple_id,
            'partner' => $this->when($this->relationLoaded('couple'), fn() => $this->couple?->users()->where('id', '!=', $this->id)->first()?->only('id', 'name', 'email')),
            'created_at' => $this->created_at,
        ];
    }
}
