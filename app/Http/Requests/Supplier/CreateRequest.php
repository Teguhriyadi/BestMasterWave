<?php

namespace App\Http\Requests\Supplier;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nama_supplier' => ['required'],
            'alamat'        => ['required'],
            'kontak_hubungi'    => ['required'],
            'nomor_kontak'  => ['required'],
            'ketentuan_tempo_pembayaran' => ['required'],
            'no_rekening' => ['required'],
            'bank_id' => ['required'],
            'nama_rekening' => ['required'],
            'pkp' => ['required'],
            'rate_ppn' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_supplier.required' => 'Nama Supplier Wajib Diisi',
            'alamat.required'        => 'Alamat Wajib Diisi',
            'kontak_hubungi.required'        => 'Jenis Kontak Wajib Diisi',
            'nomor_kontak.required'        => 'Kontak Yang Bisa Dihubungi Wajib Diisi',
            'ketentuan_tempo_pembayaran.required' => 'Alamat Wajib Diisi',
            'no_rekening.required'        => 'No. Rekening Wajib Diisi',
            'bank_id.required'      => 'Nama Bank Wajib Diisi',
            'nama_rekening.required'        => 'Nama Rekening Wajib Diisi',
            'pkp.required'        => 'PKP Wajib Diisi',
            'rate_ppn.required'        => 'Rate PPN Wajib Diisi',
        ];
    }
}
