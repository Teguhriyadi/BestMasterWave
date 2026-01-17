<?php

namespace App\Http\Mapper;

use App\Models\Bank;
use App\Models\DendaKaryawan;
use App\Models\Karyawan;
use App\Models\LogKaryawan;
use App\Models\PeringatanKaryawan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class KaryawanMapper
{
    public static function toTable(Collection $karyawan): Collection
    {
        return $karyawan->map(function(Karyawan $item) {
            $noKtp = empty($item['no_ktp'])
                ? '<span class="badge bg-danger text-white text-uppercase">Belum Upload</span>'
                : $item['no_ktp'];

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
                "no_ktp" => $noKtp,
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

    public static function toDendaKaryawanById(Collection $denda): Collection
    {
        return $denda->map(function(DendaKaryawan $item) {
            return [
                'id'            => $item["id"],
                "tanggal_denda" => $item->tanggal_denda->locale('id')->translatedFormat('d F Y'),
                "kode"          => $item["jenis_denda"]["kode"],
                "jenis_denda"   => $item["jenis_denda"]["nama_jenis"],
                "keterangan"    => $item["keterangan"],
                "nominal"       => number_format($item->jenis_denda->nominal, 0, ',', '.'),
                "periode_gaji"  => $item->periode_gaji->locale('id')->translatedFormat('d F Y'),
            ];
        });
    }

    public static function toPelanggaranKaryawanById(Collection $denda): Collection
    {
        return $denda->map(function(PeringatanKaryawan $item) {
            return [
                'id'            => $item["id"],
                "tanggal_pelanggaran" => $item->tanggal_pelanggaran->locale('id')->translatedFormat('d F Y'),
                "tanggal_terbit_sp" => $item->tanggal_terbit_sp->locale('id')->translatedFormat('d F Y'),
                "berlaku_sampai" => $item->berlaku_sampai->locale('id')->translatedFormat('d F Y'),
                "kode"          => $item["jenis_peringatan"]["kode"],
                "jenis_pelanggaran"   => $item["jenis_peringatan"]["nama_peringatan"],
                "keterangan"    => $item["keterangan"]
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
