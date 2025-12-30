<?php

namespace App\Http\Repositories;

use App\Models\Barang;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;

class BarangRepository
{
    public function get_all_data()
    {
        return Barang::orderBy("created_at", "DESC")->get();
    }

    public function insert_data(array $data)
    {
        $supplier = Barang::create([
            "sku_barang" => $data["sku_barang"],
            "harga_modal" => $data["harga_modal"],
            "harga_pembelian_terakhir" => $data["harga_pembelian_terakhir"],
            "tanggal_pembelian_terakhir" => $data["tanggal_pembelian_terakhir"],
            "status_sku" => $data["status_sku"],
            "seller_id" => $data["seller_id"],
            "created_by" => Auth::user()->id
        ]);

        return $supplier;
    }

    public function get_data_by_id(string $id)
    {
        return Barang::where("id", $id)->first();
    }

    public function update_by_id(string $id, array $data)
    {
        $supplier = Barang::findOrFail($id);

        $supplier->update([
            "sku_barang" => $data["sku_barang"],
            "harga_modal" => $data["harga_modal"],
            "harga_pembelian_terakhir" => $data["harga_pembelian_terakhir"],
            "tanggal_pembelian_terakhir" => $data["tanggal_pembelian_terakhir"],
            "status_sku" => $data["status_sku"],
            "seller_id" => $data["seller_id"],
            "updated_by" => Auth::user()->id
        ]);

        return $supplier;
    }

    public function delete_by_id(string $id): void
    {
        $supplier = Barang::findOrFail($id);
        $supplier->delete();
    }
}
