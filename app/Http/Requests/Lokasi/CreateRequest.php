<?php

namespace App\Http\Requests\Lokasi;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'kode_lokasi' => ['required', 'numeric', 'unique:lokasi,kode_lokasi'],
            'nama_lokasi' => ['required']
        ];
    }

    public function messages(): array
    {
        return [
            'kode_lokasi.required' => 'Kode Lokasi Wajib Diisi',
            'kode_lokasi.numeric'  => 'Kode Lokasi Harus Angka',
            'kode_lokasi.unique'   => 'Kode Lokasi Sudah Digunakan',
            'nama_lokasi.required' => 'Nama Lokasi Wajib Diisi'
        ];
    }
}
