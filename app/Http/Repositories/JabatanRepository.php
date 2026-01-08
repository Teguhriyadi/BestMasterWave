<?php

namespace App\Http\Repositories;

use App\Models\Divisi;
use App\Models\Jabatan;
use Illuminate\Support\Str;

class JabatanRepository
{
    public function get_all_data()
    {
        return Jabatan::orderBy("created_at", "DESC")->get();
    }

    public function insert_data(array $data)
    {
        $jabatan = Jabatan::create([
            "nama_jabatan" => $data["nama_jabatan"],
            "slug" => Str::slug($data["nama_jabatan"])
        ]);

        return $jabatan;
    }

    public function get_data_by_id(string $id)
    {
        return Jabatan::where("id", $id)->first();
    }

    public function update_by_id(string $id, array $data)
    {
        $jabatan = Jabatan::findOrFail($id);

        $jabatan->update([
            "nama_jabatan" => $data["nama_jabatan"],
            "slug" => Str::slug($data["nama_jabatan"])
        ]);

        return $jabatan;
    }

    public function delete_by_id(string $id): void
    {
        $jabatan = Jabatan::findOrFail($id);
        $jabatan->delete();
    }
}
