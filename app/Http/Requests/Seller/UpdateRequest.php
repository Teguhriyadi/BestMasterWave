<?php

namespace App\Http\Requests\Seller;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'platform_id' => ['required', 'uuid', 'exists:platform,id'],
            'nama'        => ['required', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'platform_id.required' => 'Platform Wajib Diisi',
            'nama.required'        => 'Nama Seller Wajib Diisi',
        ];
    }
}
