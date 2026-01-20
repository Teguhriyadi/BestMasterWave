<?php

namespace App\Http\Requests\Paket;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'sku_paket' => [
                'required',
                Rule::unique('paket', 'sku_paket')
                    ->ignore($this->route('id')),
            ],
            "nama_paket"    => ['required']
        ];
    }

    public function messages(): array
    {
        return [
            "sku_paket.required"    => 'SKU Paket Wajib Diisi',
            "sku_paket.unique"   => 'SKU Paket Sudah Digunakan',
            "nama_paket.required"   => "Nama Paket Wajib Diisi"
        ];
    }
}
