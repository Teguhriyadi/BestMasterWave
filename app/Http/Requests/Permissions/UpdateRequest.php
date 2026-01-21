<?php

namespace App\Http\Requests\Permissions;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nama'          => ['required'],
            'akses'         => ['required'],
            'menu_id'       => ['required'],
            'tipe_akses'    => ['required', 'array', 'min:1'],
            'tipe_akses.*'  => ['in:read,create,edit,delete,show,change_status'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required'         => 'Nama Permissions Wajib Diisi',
            'akses.required'        => 'Akses Permissions Wajib Diisi',
            'menu_id.required'      => 'Nama Menu Wajib Diisi',
            'tipe_akses.required'   => 'Tipe Akses Wajib Dipilih',
            'tipe_akses.array'      => 'Format Tipe Akses Tidak Valid',
            'tipe_akses.min'        => 'Minimal Pilih 1 Tipe Akses',
        ];
    }
}
