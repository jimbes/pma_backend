<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMedicationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'string|max:255',
            'dosage' => 'string',
            'unit' => 'string',
            'description' => 'nullable|string',
            'active' => 'boolean',
        ];
    }
}
