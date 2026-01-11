<?php

namespace App\Http\Requests\Menu;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nama_menu' => ['required'],
            'tipe_menu' => ['required', 'in:header,menu,submenu'],
            'url'  => ['nullable', 'required_if:tipe_menu,menu,submenu'],
            'parent_id' => ['nullable', 'required_if:tipe_menu,submenu'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_menu.required' => 'Nama menu wajib diisi',

            'tipe_menu.required' => 'Tipe menu wajib dipilih',
            'tipe_menu.in'       => 'Tipe menu tidak valid',

            'url.required_if' =>
            'URL wajib diisi untuk menu atau submenu',

            'parent_id.required_if' =>
            'Parent menu wajib dipilih untuk submenu',
        ];
    }
}
