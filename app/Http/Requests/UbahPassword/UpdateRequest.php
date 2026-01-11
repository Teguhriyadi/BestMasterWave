<?php

namespace App\Http\Requests\UbahPassword;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'password' => ['required', 'min:6'],
            'confirm_password' => ['required', 'same:password', 'min:6']
        ];
    }

    public function messages(): array
    {
        return [
            'password.required' => 'Password Wajib Diisi',
            'password.min' => 'Password minimal 6 karakter',
            'confirm_password.required' => 'Konfirmasi Password Wajib Diisi',
            'confirm_password.min' => 'Konfirmasi Password minimal 6 karakter',
            'confirm_password.same' => 'Konfirmasi Password harus sama dengan Password',
        ];
    }
}
