<?php

namespace App\Http\Repositories;

use App\Helpers\AuthDivisi;
use App\Models\Lokasi;

class LokasiRepository
{
    public function get_all_data()
    {
        return Lokasi::orderBy("created_at", "DESC")->get();
    }

    public function insert_data(array $data)
    {
        $lokasi = Lokasi::create([
            "kode_lokasi" => $data["kode_lokasi"],
            "nama_lokasi" => $data["nama_lokasi"],
            "divisi_id" => AuthDivisi::id()
        ]);

        return $lokasi;
    }

    public function get_data_by_id(string $id)
    {
        return Lokasi::where("id", $id)->first();
    }

    public function update_by_id(string $id, array $data)
    {
        $lokasi = Lokasi::findOrFail($id);

        $lokasi->update([
            "kode_lokasi" => $data["kode_lokasi"],
            "nama_lokasi" => $data["nama_lokasi"],
            "divisi_id" => AuthDivisi::id()
        ]);

        return $lokasi;
    }

    public function delete_by_id(string $id): void
    {
        $lokasi = Lokasi::findOrFail($id);
        $lokasi->delete();
    }
}
