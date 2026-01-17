<?php

namespace App\Http\Requests\KetidakHadiran;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'karyawan_id'   => ['required'],
            'status'        => ['required'],
            'tanggal'       => ['required']
        ];
    }

    public function messages(): array
    {
        return [
            'karyawan_id.required'  => 'Nama Karyawan Wajib Diisi',
            'status.required'       => 'Status Wajib Diisi',
            'tanggal.required'      => 'Tanggal Wajib Diisi'
        ];
    }
}
