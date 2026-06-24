<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MedicationResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'dosage' => $this->dosage,
            'unit' => $this->unit,
            'description' => $this->description,
            'active' => $this->active,
            'schedules_count' => $this->when($this->relationLoaded('schedules'), fn() => $this->schedules->count()),
            'schedules' => MedicationScheduleResource::collection($this->whenLoaded('schedules')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
