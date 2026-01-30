<?php

namespace App\Http\Requests\Tiktok\HargaModal;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'dari'        => ['required', 'date', 'before_or_equal:sampai'],
            'sampai'      => ['required', 'date', 'after_or_equal:dari'],
            'harga_modal' => ['required', 'numeric']
        ];
    }

    public function messages(): array
    {
        return [
            'dari.required'            => 'Dari Tanggal Wajib Diisi',
            'dari.before_or_equal'     => 'Dari Tanggal tidak boleh lebih besar dari Sampai Tanggal',
            'sampai.required'          => 'Sampai Tanggal Wajib Diisi',
            'sampai.after_or_equal'    => 'Sampai Tanggal tidak boleh lebih kecil dari Dari Tanggal',
            'harga_modal.required'     => 'Harga Modal Wajib Diisi',
            'harga_modal.numeric'      => 'Harga Modal harus berupa angka'
        ];
    }
}
