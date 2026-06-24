<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMedicationScheduleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'nullable|date',
            'frequency' => 'in:daily,specific_days',
            'days_of_week' => 'nullable|json',
            'reminder_times' => 'json',
            'reminder_offset_hours' => 'integer|min:0|max:24',
            'notify_user_1' => 'boolean',
            'notify_user_2' => 'boolean',
        ];
    }
}
