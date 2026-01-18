<?php

namespace App\Http\Requests\SetupJamKerja;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'jam_masuk' => ['required'],
            'jam_pulang' => ['required'],
            'toleransi_menit' => ['required'],
            'divisi_id' => ['required']
        ];
    }

    public function messages(): array
    {
        return [
            'jam_masuk.required' => 'Jam Masuk Wajib Diisi',
            'jam_pulang.required' => 'Jam Pulang Wajib Diisi',
            'toleransi_menit.required' => 'Toleransi Menit Wajib Diisi',
            'divisi_id.required' => 'Nama Divisi Wajib Diisi'
        ];
    }
}
