<?php

namespace App\Http\Repositories;

use App\Models\Divisi;

class DivisiRepository
{
    public function get_all_data()
    {
        return Divisi::orderBy("created_at", "DESC")->get();
    }

    public function insert_data(array $data)
    {
        $divisi = Divisi::create([
            "nama_divisi" => $data["nama_divisi"]
        ]);

        return $divisi;
    }

    public function get_data_by_id(string $id)
    {
        return Divisi::where("id", $id)->first();
    }

    public function update_by_id(string $id, array $data)
    {
        $divisi = Divisi::findOrFail($id);

        $divisi->update([
            "nama_divisi" => $data["nama_divisi"],
        ]);

        return $divisi;
    }

    public function delete_by_id(string $id): void
    {
        $supplier = Divisi::findOrFail($id);
        $supplier->delete();
    }
}
