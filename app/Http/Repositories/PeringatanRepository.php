<?php

namespace App\Http\Repositories;

use App\Models\PeringatanKaryawan;
use Illuminate\Support\Facades\Auth;

class PeringatanRepository
{
    public function get_all_data()
    {
        return PeringatanKaryawan::orderBy("created_at", "DESC")->get();
    }

    public function insert_data(array $data)
    {
        $peringatan_karyawan = PeringatanKaryawan::create([
            "karyawan_id" => $data["karyawan_id"],
            "jenis_peringatan_id" => $data["jenis_peringatan_id"],
            "tanggal_pelanggaran" => $data["tanggal_pelanggaran"],
            "tanggal_terbit_sp" => $data["tanggal_terbit_sp"],
            "berlaku_sampai"    => $data["berlaku_sampai"],
            "keterangan"        => $data["keterangan"],
            "status"            => "Draft",
            "created_by"        => Auth::user()->id
        ]);

        return $peringatan_karyawan;
    }

    public function get_data_by_id(string $id)
    {
        return PeringatanKaryawan::where("id", $id)->first();
    }

    public function update_by_id(string $id, array $data)
    {
        $divisi = PeringatanKaryawan::findOrFail($id);

        $divisi->update([
            "karyawan_id" => $data["karyawan_id"],
            "jenis_peringatan_id" => $data["jenis_peringatan_id"],
            "tanggal_pelanggaran" => $data["tanggal_pelanggaran"],
            "tanggal_terbit_sp" => $data["tanggal_terbit_sp"],
            "berlaku_sampai"    => $data["berlaku_sampai"],
            "keterangan"        => $data["keterangan"]
        ]);

        return $divisi;
    }

    public function delete_by_id(string $id): void
    {
        $peringatan_karyawan = PeringatanKaryawan::findOrFail($id);
        $peringatan_karyawan->delete();
    }
}
