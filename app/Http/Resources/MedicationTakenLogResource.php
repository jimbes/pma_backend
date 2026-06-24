<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MedicationTakenLogResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'medication_schedule_id' => $this->medication_schedule_id,
            'date' => $this->date,
            'taken' => $this->taken,
            'taken_at' => $this->taken_at,
            'user_logged_by' => $this->user_logged_by,
            'notes' => $this->notes,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
