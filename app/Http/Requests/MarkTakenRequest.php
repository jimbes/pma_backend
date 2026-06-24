<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MarkTakenRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'date' => 'required|date',
            'notes' => 'nullable|string',
        ];
    }
}
