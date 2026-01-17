<?php

namespace App\Http\Requests\Peringatan;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'karyawan_id'           => ['required'],
            'jenis_peringatan_id'   => ['required'],
            'tanggal_pelanggaran'   => ['required'],
            'tanggal_terbit_sp'     => ['required'],
            'berlaku_sampai'        => ['required'],
            'keterangan'            => ['required']
        ];
    }

    public function messages(): array
    {
        return [
            'karyawan_id.required'          => 'Nama Karyawan Wajib Diisi',
            'jenis_peringatan_id.required'  => 'Jenis Peringatan Wajib Diisi',
            'tanggal_pelanggaran.required'  => 'Tanggal Pelanggaran Wajib Diisi',
            'tanggal_terbit_sp.required'    => 'Tanggal Terbit SP Wajib Diisi',
            'berlaku_sampai.required'       => 'Berlaku Sampai Wajib Diisi',
            'keterangan.required'           => 'Keterangan Wajib Diisi'
        ];
    }
}
