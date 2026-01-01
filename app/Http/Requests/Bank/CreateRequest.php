<?php

namespace App\Http\Requests\Bank;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nama_bank' => ['required'],
            'alias'        => ['required', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_bank.required' => 'Nama Bank Wajib Diisi',
            'alias.required'        => 'Nama Alias Bank Wajib Diisi',
        ];
    }
}
