<?php

namespace App\Http\Repositories;

use App\Models\SetupJamKerja;
use Illuminate\Support\Facades\Auth;

class SetupJamKerjaRepository
{
    public function get_all_data()
    {
        return SetupJamKerja::orderBy("created_at", "DESC")->get();
    }

    public function insert_data(array $data)
    {
        $setup_jam_kerja = SetupJamKerja::create([
            "jam_masuk" => $data["jam_masuk"],
            "jam_pulang" => $data["jam_pulang"],
            "toleransi_menit" => $data["toleransi_menit"],
            "divisi_id" => $data["divisi_id"],
            "created_by" => Auth::user()->id
        ]);

        return $setup_jam_kerja;
    }

    public function get_data_by_id(string $id)
    {
        return SetupJamKerja::where("id", $id)->first();
    }

    public function update_by_id(string $id, array $data)
    {
        $setup_jam_kerja = SetupJamKerja::findOrFail($id);

        $setup_jam_kerja->update([
            "jam_masuk" => $data["jam_masuk"],
            "jam_pulang" => $data["jam_pulang"],
            "toleransi_menit" => $data["toleransi_menit"],
            "divisi_id" => $data["divisi_id"],
            "updated_by" => Auth::user()->id
        ]);

        return $setup_jam_kerja;
    }

    public function delete_by_id(string $id): void
    {
        $setup_jam_kerja = SetupJamKerja::findOrFail($id);
        $setup_jam_kerja->delete();
    }
}
