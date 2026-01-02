<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nama_role' => ['required']
        ];
    }

    public function messages(): array
    {
        return [
            'nama_role.required' => 'Nama Role Wajib Diisi'
        ];
    }
}
