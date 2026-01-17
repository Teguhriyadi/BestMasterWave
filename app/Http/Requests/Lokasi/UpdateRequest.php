<?php

namespace App\Http\Requests\Lokasi;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'kode_lokasi' => [
                'required',
                'numeric',
                Rule::unique('lokasi', 'kode_lokasi')
                    ->ignore($this->route('id')),
            ],
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
