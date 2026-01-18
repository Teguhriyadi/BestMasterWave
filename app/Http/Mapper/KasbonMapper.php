<?php

namespace App\Http\Mapper;

use App\Models\Kasbon;
use Illuminate\Support\Collection;

class KasbonMapper
{
    public static function toTable(Collection $kasbon): Collection
    {
        return $kasbon->map(function (Kasbon $item) {
            return [
                'id' => $item->id,
                'karyawan' => [
                    'nama'    => $item->karyawan->nama ?? '-',
                    'jabatan' => $item->karyawan->jabatan->nama_jabatan ?? '-',
                ],
                'tanggal_mulai' => $item->tanggal_mulai
                    ? $item->tanggal_mulai->locale('id')->translatedFormat('d F Y')
                    : '-',
                'jumlah_awal' => number_format($item->jumlah_awal, 0, ',', '.'),
                'sisa'        => number_format($item->sisa, 0, ',', '.'),
                'status'      => $item->status,
                'keterangan'  => $item->keterangan ?? '-',
            ];
        });
    }
}
