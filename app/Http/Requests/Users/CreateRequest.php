<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Validator;

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

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {

            $exists = DB::table('users')
                ->join('users_divisi_role', 'users.id', '=', 'users_divisi_role.user_id')
                ->where('users.username', $this->username)
                ->where('users_divisi_role.divisi_id', $this->divisi_id)
                ->where('users_divisi_role.role_id', $this->role_id)
                ->exists();

            if ($exists) {
                $validator->errors()->add(
                    'username',
                    'Username dengan divisi dan role tersebut sudah terdaftar'
                );
            }
        });
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
