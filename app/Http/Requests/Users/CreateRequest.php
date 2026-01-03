<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nama' => ['required'],
            'username' => ['required'],
            'email' => ['required'],
            'divisi_id' => ['required'],
            'role_id' => ['required']
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Platform Wajib Diisi',
            'username.required' => 'Username Wajib Diisi',
            'email.required' => 'Email Wajib Diisi',
            'divisi_id.required' => 'Nama Divisi Wajib Diisi',
            'role_id.required' => 'Nama Role Wajib Diisi'
        ];
    }
}
