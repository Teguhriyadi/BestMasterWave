<?php

namespace App\Http\Requests\Platform;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'platform' => ['required']
        ];
    }

    public function messages(): array
    {
        return [
            'platform.required' => 'Nama Platform Wajib Diisi'
        ];
    }
}
