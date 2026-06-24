<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterDeviceTokenRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'token' => 'required|string',
            'platform' => 'required|in:flutter,pwa',
        ];
    }
}
