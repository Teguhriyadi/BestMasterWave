<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'username' => ['required'],
            'password'        => ['required']
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'Username Wajib Diisi',
            'password.required'        => 'Password Wajib Diisi'
        ];
    }
}
