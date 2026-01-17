<?php

namespace App\Http\Repositories;

use App\Models\Divisi;
use App\Models\RekapAbsensi;
use Illuminate\Support\Facades\Auth;

class KetidakHadiranRepository
{
    public function get_all_data()
    {
        return RekapAbsensi::orderBy("created_at", "DESC")->get();
    }

    public function insert_data(array $data)
    {
        $rekap_absensi = RekapAbsensi::create([
            "karyawan_id" => $data["karyawan_id"],
            "status"    => $data["status"],
            "alasan"    => $data["alasan"] ?? null,
            "foto"      => $data["foto"] ?? null,
            "tanggal"   => $data["tanggal"],
            "created_by" => Auth::user()->id
        ]);

        return $rekap_absensi;
    }

    public function get_data_by_id(string $id)
    {
        return RekapAbsensi::where("id", $id)->first();
    }

    public function update_by_id(string $id, array $data)
    {
        $rekap_absensi = RekapAbsensi::findOrFail($id);

        $rekap_absensi->update([
            "karyawan_id" => $data["karyawan_id"],
            "status"    => $data["status"],
            "alasan"    => $data["alasan"] ?? null,
            "foto"      => $data["foto"] ?? null,
            "tanggal"   => $data["tanggal"],
            "updated_by" => Auth::user()->id
        ]);

        return $rekap_absensi;
    }

    public function delete_by_id(string $id): void
    {
        $ketidakhadiran = RekapAbsensi::findOrFail($id);
        $ketidakhadiran->delete();
    }

    public function getRolesByDivisi(string $divisionId)
    {
        return Divisi::with('roles:id,nama_role')
            ->findOrFail($divisionId)
            ->roles;
    }
}
