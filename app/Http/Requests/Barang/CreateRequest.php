<?php

namespace App\Http\Requests\Barang;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'sku_barang' => ['required'],
            'harga_modal' => ['required'],
            'harga_pembelian_terakhir' => ['required'],
            'tanggal_pembelian_terakhir' => ['required'],
            'status_sku' => ['required'],
            'seller_id' => ['required']
        ];
    }

    public function messages(): array
    {
        return [
            'sku_barang.required' => 'SKU Barang Wajib Diisi',
            'harga_modal.required' => 'Harga Modal Wajib Diisi',
            'harga_pembelian_terakhir.required' => 'Harga Pembelian Terakhir Wajib Diisi',
            'tanggal_pembelian_terakhir.required' => 'Tanggal Pembelian Terakhir Wajib Diisi',
            'status_sku.required' => 'Status SKU Wajib Diisi',
            'seller_id.required' => 'Nama Seller Wajib Diisi'
        ];
    }
}
