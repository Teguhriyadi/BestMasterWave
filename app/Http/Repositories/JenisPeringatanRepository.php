<?php

namespace App\Http\Repositories;

use App\Models\JenisPeringatan;
use Illuminate\Support\Facades\Auth;

class JenisPeringatanRepository
{
    public function get_all_data()
    {
        return JenisPeringatan::orderBy("created_at", "DESC")->get();
    }

    public function insert_data(array $data)
    {
        $jenis_peringatan = JenisPeringatan::create([
            "kode"              => $data["kode"],
            "nama_peringatan"   => $data["nama_peringatan"],
            "level"             => $data["level"],
            "masa_berlaku_hari" => $data["masa_berlaku_hari"],
            "keterangan"        => $data["keterangan"],
            "created_by"        => Auth::user()->id
        ]);

        return $jenis_peringatan;
    }

    public function get_data_by_id(string $id)
    {
        return JenisPeringatan::where("id", $id)->first();
    }

    public function update_by_id(string $id, array $data)
    {
        $jenis_peringatan = JenisPeringatan::findOrFail($id);

        $jenis_peringatan->update([
            "kode"              => $data["kode"],
            "nama_peringatan"   => $data["nama_peringatan"],
            "level"             => $data["level"],
            "masa_berlaku_hari" => $data["masa_berlaku_hari"],
            "keterangan"        => $data["keterangan"],
            "updated_by"        => Auth::user()->id
        ]);

        return $jenis_peringatan;
    }

    public function delete_by_id(string $id): void
    {
        $jenis_peringatan = JenisPeringatan::findOrFail($id);
        $jenis_peringatan->delete();
    }
}
