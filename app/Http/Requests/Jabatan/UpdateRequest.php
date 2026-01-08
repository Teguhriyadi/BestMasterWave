<?php

namespace App\Http\Requests\Jabatan;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nama_jabatan' => ['required']
        ];
    }

    public function messages(): array
    {
        return [
            'nama_jabatan.required' => 'Nama Jabatan Wajib Diisi'
        ];
    }
}
