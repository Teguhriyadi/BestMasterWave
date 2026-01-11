<?php

namespace App\Http\Requests\Permissions;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nama'      => ['required'],
            'akses'     => ['required'],
            'menu_id'   => ['required']
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required'     => 'Nama Permissions Wajib Diisi',
            'akses.required'    => "Akses Permissions Wajib Diisi",
            'menu_id.required'  => "Nama Menu Wajib Diisi"
        ];
    }
}
