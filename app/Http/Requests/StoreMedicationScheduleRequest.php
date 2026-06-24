<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMedicationScheduleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'medication_id' => 'required|exists:medications,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'frequency' => 'required|in:daily,specific_days',
            'days_of_week' => 'nullable|json',
            'reminder_times' => 'required|json',
            'reminder_offset_hours' => 'integer|min:0|max:24',
            'notify_user_1' => 'boolean',
            'notify_user_2' => 'boolean',
        ];
    }
}
