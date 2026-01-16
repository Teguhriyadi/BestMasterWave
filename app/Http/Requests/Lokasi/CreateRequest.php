<?php

namespace App\Http\Requests\Lokasi;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'kode_lokasi' => ['required'],
            'nama_lokasi' => ['required']
        ];
    }

    public function messages(): array
    {
        return [
            'kode_lokasi.required' => 'Kode Lokasi Wajib Diisi',
            'nama_lokasi.required' => 'Nama Lokasi Wajib Diisi'
        ];
    }
}
