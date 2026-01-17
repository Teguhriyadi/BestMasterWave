<?php

namespace App\Http\Requests\JenisPeringatan;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'kode' => [
                'required',
                Rule::unique('jenis_peringatan', 'kode')
                    ->ignore($this->route('id')),
            ],
            'nama_peringatan'   => ['required'],
            'level'             => ['required'],
            'masa_berlaku_hari' => ['required'],
            'keterangan'        => ['required']
        ];
    }

    public function messages(): array
    {
        return [
            'kode.required'              => 'Kode Wajib Diisi',
            'kode.unique'                => 'Kode Sudah Digunakan',
            'nama_peringatan.required'   => 'Nama Peringatan Wajib Diisi',
            'level.required'             => 'Level Peringatan Wajib Diisi',
            'masa_berlaku_hari.required' => 'Masa Berlaku Hari Wajib Diisi',
            'keterangan.required'        => 'Keterangan Wajib Diisi'
        ];
    }
}
