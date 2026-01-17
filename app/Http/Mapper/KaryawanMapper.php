<?php

namespace App\Http\Mapper;

use App\Models\Bank;
use App\Models\Karyawan;
use App\Models\LogKaryawan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class KaryawanMapper
{
    public static function toTable(Collection $karyawan): Collection
    {
        return $karyawan->map(function(Karyawan $item) {
            $statusNoKK = empty($item['no_kk'])
                ? '<span class="badge bg-danger text-white text-uppercase">Belum Upload</span>'
                : '<span class="badge bg-success text-white text-uppercase">Sudah Upload</span>';

            $statusBPJS = empty($item['no_bpjs_kesehatan'])
                ? '<span class="badge bg-danger text-white text-uppercase">Belum Upload</span>'
                : '<span class="badge bg-success text-white text-uppercase">Sudah Upload</span>';

            $statusAccNo = empty($item['acc_no'])
                ? '<span class="badge bg-danger text-white text-uppercase">Belum Upload</span>'
                : '<span class="badge bg-success text-white text-uppercase">Sudah Upload</span>';

            return [
                'id' => $item["id"],
                "sidik_jari" => $item["id_fp"],
                "no_ktp" => $item["no_ktp"],
                "no_kk" => $statusNoKK,
                "no_bpjs_kesehatan" => $statusBPJS,
                "acc_no"    => $statusAccNo,
                "nama" => $item["nama"],
                "tanggal_masuk" => $item->tanggal_masuk->locale('id')->translatedFormat('d F Y'),
                "no_hp" => $item["no_hp"],
                "no_hp_darurat" => $item["no_hp_darurat"],
                "jenis_kelamin" => $item["jenis_kelamin"] == "L" ? "Laki - Laki" : "Perempuan",
                "divisi" => !empty(Auth::user()->one_divisi_roles) ? "A" : $item["divisi"]["nama_divisi"]
            ];
        });
    }

    public static function toListKaryawan(Collection $karyawan): Collection
    {
        return $karyawan->map(function(Karyawan $item) {
            return [
                'id' => $item["id"],
                "nama" => $item["nama"],
                "jabatan" => $item["jabatan"]["nama_jabatan"]
            ];
        });
    }

    public static function toListLogKaryawan(Collection $log): Collection
    {
        return $log->map(function(LogKaryawan $item) {
            return [
                'id'             => $item["id"],
                "deskripsi"      => $item["deskripsi"],
                "dibuat_oleh"    => $item["users"]["nama"],
                "dibuat_tanggal" => $item->created_at->locale('id')->translatedFormat('d F Y H:i:s'),
                "diubah_tanggal" => $item->updated_at->locale('id')->translatedFormat('d F Y H:i:s'),
            ];
        });
    }
}
