<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'appointment_date' => 'required|date',
            'appointment_time' => 'nullable|date_format:H:i',
            'location' => 'nullable|string',
            'doctor_name' => 'nullable|string',
            'description' => 'nullable|string',
            'notify_user_1' => 'boolean',
            'notify_user_2' => 'boolean',
        ];
    }
}
