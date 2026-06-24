<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMedicationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'dosage' => 'required|string',
            'unit' => 'required|string',
            'description' => 'nullable|string',
        ];
    }
}
