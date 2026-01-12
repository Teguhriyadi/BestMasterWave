<?php

namespace App\Http\Requests\Barang;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'sku_barang' => ['required', 'unique:barang,sku_barang'],
            'harga_modal' => ['required']
        ];
    }

    public function messages(): array
    {
        return [
            'sku_barang.required' => 'SKU Barang Wajib Diisi',
            'sku_barang.unique'   => 'SKU Barang Sudah Digunakan',
            'harga_modal.required' => 'Harga Modal Wajib Diisi'
        ];
    }
}
