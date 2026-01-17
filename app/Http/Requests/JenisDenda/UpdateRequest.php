<?php

namespace App\Http\Requests\JenisDenda;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'kode' => [
                'required',
                Rule::unique('jenis_denda', 'kode')
                    ->ignore($this->route('id')),
            ],
            'nama_jenis' => ['required'],
            'nominal' => ['required'],
            'keterangan' => ['required']
        ];
    }

    public function messages(): array
    {
        return [
            'kode.required'         => 'Kode Wajib Diisi',
            'kode.unique'           => 'Kode Sudah Digunakan',
            'nama_jenis.required'   => 'Nama Jenis Denda Wajib Diisi',
            'nominal.required'      => 'Nominal Denda Wajib Diisi',
            'keterangan.required'   => 'Keterangan Wajib Diisi'
        ];
    }
}
