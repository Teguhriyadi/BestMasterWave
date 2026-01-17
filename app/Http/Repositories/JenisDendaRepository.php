<?php

namespace App\Http\Repositories;

use App\Models\JenisDenda;
use Illuminate\Support\Facades\Auth;

class JenisDendaRepository
{
    public function get_all_data()
    {
        return JenisDenda::orderBy("created_at", "DESC")->get();
    }

    public function insert_data(array $data)
    {
        $jenis_denda = JenisDenda::create([
            "kode"          => $data["kode"],
            "nama_jenis"    => $data["nama_jenis"],
            "nominal"       => $data["nominal"],
            "keterangan"    => $data["keterangan"],
            "created_by"    => Auth::user()->id
        ]);

        return $jenis_denda;
    }

    public function get_data_by_id(string $id)
    {
        return JenisDenda::where("id", $id)->first();
    }

    public function update_by_id(string $id, array $data)
    {
        $jenis_denda = JenisDenda::findOrFail($id);

        $jenis_denda->update([
            "kode"          => $data["kode"],
            "nama_jenis"    => $data["nama_jenis"],
            "nominal"       => $data["nominal"],
            "keterangan"    => $data["keterangan"],
            "updated_by"    => Auth::user()->id
        ]);

        return $jenis_denda;
    }

    public function delete_by_id(string $id): void
    {
        $jenis_denda = JenisDenda::findOrFail($id);
        $jenis_denda->delete();
    }
}
