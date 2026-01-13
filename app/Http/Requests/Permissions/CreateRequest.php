<?php

namespace App\Http\Requests\Permissions;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nama'       => ['required'],
            'akses' => [
                'required',
                Rule::unique('permissions')
                    ->where(fn ($q) =>
                        $q->where("akses", $this->akses . "." . $this->tipe_akses)
                            ->where('menu_id', $this->menu_id)
                    )
            ],
            'menu_id'    => ['required'],
            'tipe_akses' => ['required']
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required'       => 'Nama Permissions Wajib Diisi',
            'akses.required'      => "Akses Permissions Wajib Diisi",
            'akses.unique'        => 'Menu sudah ada',
            'menu_id.required'    => "Nama Menu Wajib Diisi",
            'tipe_akses.required' => "Tipe Menu Wajib Diisi"
        ];
    }
}
