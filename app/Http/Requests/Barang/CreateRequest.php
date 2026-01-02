<?php

namespace App\Http\Requests\Barang;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'sku_barang' => ['required'],
            'harga_modal' => ['required']
        ];
    }

    public function messages(): array
    {
        return [
            'sku_barang.required' => 'SKU Barang Wajib Diisi',
            'harga_modal.required' => 'Harga Modal Wajib Diisi'
        ];
    }
}
