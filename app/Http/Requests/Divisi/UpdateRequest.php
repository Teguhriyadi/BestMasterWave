<?php

namespace App\Http\Requests\Divisi;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nama_divisi' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_divisi.required' => 'Nama Divisi Wajib Diisi'
        ];
    }
}
