<?php

namespace App\Http\Requests\Paket;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "sku_paket"     => ['required', 'unique:paket,sku_paket'],
            "nama_paket"    => ['required']
        ];
    }

    public function messages(): array
    {
        return [
            "sku_paket.required"    => 'SKU Paket Wajib Diisi',
            'sku_paket.unique'   => 'SKU Paket Sudah Digunakan',
            "nama_paket.required"   => "Nama Paket Wajib Diisi"
        ];
    }
}
