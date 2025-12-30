<?php

namespace App\Http\Repositories;

use App\Models\Bank;
use Illuminate\Support\Str;

class BankRepository
{
    public function get_all_data()
    {
        return Bank::orderBy("created_at", "DESC")->get();
    }

    public function insert_data(array $data)
    {
        $supplier = Bank::create([
            "nama_bank" => $data["nama_bank"],
            "alias" => $data["alias"],
            "slug_bank" => Str::slug($data["nama_bank"])
        ]);

        return $supplier;
    }

    public function get_data_by_id(string $id)
    {
        return Bank::where("id", $id)->first();
    }

    public function update_by_id(string $id, array $data)
    {
        $supplier = Bank::findOrFail($id);

        $supplier->update([
            "nama_bank" => $data["nama_bank"],
            "alias" => $data["alias"],
            "slug_bank" => $data["slug_bank"]
        ]);

        return $supplier;
    }

    public function delete_by_id(string $id): void
    {
        $supplier = Bank::findOrFail($id);
        $supplier->delete();
    }
}
