<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'appointment_date' => $this->appointment_date,
            'appointment_time' => $this->appointment_time,
            'location' => $this->location,
            'doctor_name' => $this->doctor_name,
            'notify_user_1' => $this->notify_user_1,
            'notify_user_2' => $this->notify_user_2,
            'completed' => $this->completed,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
