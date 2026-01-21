<?php

namespace App\Http\Repositories;

use App\Models\Bank;
use App\Models\Paket;
use App\Models\PaketItems;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PaketRepository
{
    public function get_all_data()
    {
        return Paket::with(["items.barangs"])
            ->orderBy("created_at", "DESC")
            ->get();
    }

    public function insert_data(array $data)
    {
        $add_paket = Paket::create([
            "sku_paket" => $data["sku_paket"],
            "nama_paket" => $data["nama_paket"],
            "harga_jual" => $data["total_paket"],
            "seller_id" => $data["seller_id"],
            "created_by" => Auth::user()->id
        ]);

        return $add_paket;
    }

    public function insertItem(array $itemData)
    {
        return PaketItems::create($itemData);
    }

    public function get_data_by_id(string $id)
    {
        return Paket::where("id", $id)->first();
    }

    public function get_show_data_by_id(string $id)
    {
        return Paket::where("id", $id)->first();
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
        $paket = Paket::findOrFail($id);
        PaketItems::where("paket_id", $paket->id)->delete();
        $paket->delete();
    }

    public function update_header($id, array $data)
    {
        $paket = Paket::findOrFail($id);
        $paket->update($data);
        return $paket;
    }

    public function delete_items_by_paket_id($paketId)
    {
        return PaketItems::where('paket_id', $paketId)->delete();
    }

    public function insert_items_batch(array $items)
    {
        return PaketItems::insert($items);
    }
}
