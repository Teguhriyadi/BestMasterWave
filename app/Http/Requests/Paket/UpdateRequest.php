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
            "nama_paket"    => ['required'],
            "harga_paket"    => ['required'],
            "seller_id"     => ['required']
        ];
    }

    public function messages(): array
    {
        return [
            "sku_paket.required"    => 'SKU Paket Wajib Diisi',
            "sku_paket.unique"   => 'SKU Paket Sudah Digunakan',
            "nama_paket.required"   => "Nama Paket Wajib Diisi",
            "harga_paket.required"   => "Harga Paket Wajib Diisi",
            "seller_id.required"    => "Nama Seller Wajib Diisi"
        ];
    }
}
