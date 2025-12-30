<?php

namespace App\Http\Repositories;

use App\Models\Supplier;

class SupplierRepository
{
    public function get_all_data()
    {
        return Supplier::orderBy("created_at", "DESC")->get();
    }

    public function insert_data(array $data)
    {
        $supplier = Supplier::create([
            "nama_supplier" => $data["nama_supplier"],
            "alamat" => $data["alamat"],
            "kontak_hubungi" => $data["kontak_hubungi"],
            "nomor_kontak" => $data["nomor_kontak"],
            "ketentuan_tempo_pembayaran" => $data["ketentuan_tempo_pembayaran"],
            "no_rekening" => $data["no_rekening"],
            "bank" => $data["bank"],
            "nama_rekening" => $data["nama_rekening"],
            "pkp" => $data["pkp"],
            "no_npwp" => $data["no_npwp"],
            "rate_ppn" => empty($data["rate_ppn"]) ? 0 : $data["rate_ppn"]
        ]);

        return $supplier;
    }

    public function get_data_by_id(string $id)
    {
        return Supplier::where("id", $id)->first();
    }

    public function update_by_id(string $id, array $data)
    {
        $supplier = Supplier::findOrFail($id);

        $supplier->update([
            "nama_supplier" => $data["nama_supplier"],
            "alamat" => $data["alamat"],
            "kontak_hubungi" => $data["kontak_hubungi"],
            "nomor_kontak" => $data["nomor_kontak"],
            "ketentuan_tempo_pembayaran" => $data["ketentuan_tempo_pembayaran"],
            "no_rekening" => $data["no_rekening"],
            "bank" => $data["bank"],
            "nama_rekening" => $data["nama_rekening"],
            "pkp" => $data["pkp"],
            "no_npwp" => $data["no_npwp"],
            "rate_ppn" => empty($data["rate_ppn"]) ? 0 : $data["rate_ppn"]
        ]);

        return $supplier;
    }

    public function delete_by_id(string $id): void
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();
    }
}
