<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MedicationScheduleResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'medication_id' => $this->medication_id,
            'medication' => new MedicationResource($this->whenLoaded('medication')),
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'frequency' => $this->frequency,
            'days_of_week' => $this->days_of_week,
            'reminder_times' => $this->reminder_times,
            'reminder_offset_hours' => $this->reminder_offset_hours,
            'notify_user_1' => $this->notify_user_1,
            'notify_user_2' => $this->notify_user_2,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
