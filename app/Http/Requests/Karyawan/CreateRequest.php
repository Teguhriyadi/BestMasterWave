<?php

namespace App\Http\Requests\Karyawan;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id_sidik_jari'     => ['required'],
            'nama'              => ['required'],
            'nama_panggilan'    => ['required'],
            'tanggal_masuk'     => ['required'],
            'no_hp'             => ['required'],
            'no_hp_darurat'     => ['required'],
            'tempat_lahir'      => ['required'],
            'tanggal_lahir'     => ['required'],
            'jenis_kelamin'     => ['required'],
            'alamat'            => ['required'],
            'status_pernikahan' => ['required'],
            'jabatan_id'        => ['required']
        ];
    }

    public function messages(): array
    {
        return [
            'id_sidik_jari.required'     => 'ID Sidik Jari Wajib Diisi',
            'nama.required'              => 'Nama Wajib Diisi',
            'nama_panggilan.required'    => 'Nama Panggilan Wajib Diisi',
            'no_hp.required'             => 'Nomor Handphone Wajib Diisi',
            'no_hp_darurat.required'     => 'Nomor Handphone Darurat Wajib Diisi',
            'tanggal_masuk.required'     => 'Tanggal Masuk Wajib Diisi',
            'tempat_lahir.required'      => 'Tempat Lahir Wajib Diisi',
            'tanggal_lahir.required'     => 'Tanggal Lahir Wajib Diisi',
            'jenis_kelamin.required'     => 'Jenis Kelamin Wajib Diisi',
            'alamat.required'            => 'Alamat Wajib Diisi',
            'status_pernikahan.required' => 'Status Pernikahan Wajib Diisi',
            'jabatan_id.required'        => 'Nama Jabatan Wajib Diisi'
        ];
    }
}
