<?php

namespace App\Http\Repositories;

use App\Helpers\AuthDivisi;
use App\Models\Barang;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;

class BarangRepository
{
    public function get_all_data()
    {
        if (empty(AuthDivisi::check_data())) {
            return Barang::orderBy("created_at", "DESC")->get();
        } else {
            return Barang::where("divisi_id", AuthDivisi::id())
                ->orderBy("created_at", "DESC")
                ->get();
        }
    }

    public function insert_data(array $data)
    {
        $supplier = Barang::create([
            "sku_barang" => $data["sku_barang"],
            "harga_modal" => $data["harga_modal"],
            "harga_pembelian_terakhir" => $data['harga_pembelian_terakhir'] ?? 0,
            "tanggal_pembelian_terakhir" => $data['tanggal_pembelian_terakhir'] ?? null,
            "status_sku" => "A",
            "seller_id" => $data["seller_id"] ?? null,
            "created_by" => Auth::user()->id,
            "divisi_id" => AuthDivisi::id()
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
            "harga_pembelian_terakhir" => empty($data["harga_pembelian_terakhir"]) ? $supplier["harga_pembelian_terakhir"] : $data["harga_pembelian_terakhir"],
            "tanggal_pembelian_terakhir" => empty($data["tanggal_pembelian_terakhir"]) ? $supplier['tanggal_pembelian_terakhir'] : $data["tanggal_pembelian_terakhir"],
            "seller_id" => $data["seller_id"],
            "updated_by" => Auth::user()->id,
            "divisi_id" => AuthDivisi::id()
        ]);

        return $supplier;
    }

    public function delete_by_id(string $id): void
    {
        $supplier = Barang::findOrFail($id);
        $supplier->delete();
    }
}
