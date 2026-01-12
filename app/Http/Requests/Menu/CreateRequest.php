<?php

namespace App\Http\Requests\Menu;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nama_menu' => ['required', 'string', 'max:255'],
            'tipe_menu' => ['required', 'in:header,menu,submenu'],
            'url' => ['required_unless:tipe_menu,header', 'nullable', 'string'],
            'parent_id' => ['required_if:tipe_menu,menu,submenu', 'nullable'],
            'icon' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_menu.required' => 'Nama menu wajib diisi.',
            'tipe_menu.required' => 'Tipe menu wajib dipilih.',
            'tipe_menu.in'       => 'Tipe menu tidak valid.',
            'url.required_unless' => 'URL wajib diisi kecuali untuk tipe Header.',
            'parent_id.required_if' => 'Induk (Parent/Header) wajib dipilih.',
            'icon.required'      => 'Ikon menu wajib diisi.',
        ];
    }
}
