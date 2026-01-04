<?php

namespace App\Http\Requests\Pembelian;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'no_invoice' => ['required'],
            'supplier_id' => ['required'],
            'tanggal_invoice' => ['required'],
            'tanggal_jatuh_tempo' => ['required']
        ];
    }

    public function messages(): array
    {
        return [
            'no_invoice.required' => 'Nomor Invoice Wajib Diisi',
            'supplier_id.required' => 'Nama Supplier Wajib Diisi',
            'tanggal_invoice.required' => 'Tanggal Invoice Wajib Diisi',
            'tanggal_jatuh_tempo.required' => 'Tanggal Jatuh Tempo Wajib Diisi'
        ];
    }
}
