<?php

namespace App\Http\Repositories;

use App\Helpers\AuthDivisi;
use App\Models\Barang;
use App\Models\DetailPembelian;
use App\Models\Pembelian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembelianRepository
{
    public function get_all_data(Request $request)
    {
        $query = Pembelian::query();

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->filled('tgl_invoice_dari') && $request->filled('tgl_invoice_sampai')) {
            $query->whereBetween('tanggal_invoice', [
                $request->tgl_invoice_dari,
                $request->tgl_invoice_sampai
            ]);
        } elseif ($request->filled('tgl_invoice_dari')) {
            $query->whereDate('tanggal_invoice', '>=', $request->tgl_invoice_dari);
        } elseif ($request->filled('tgl_invoice_sampai')) {
            $query->whereDate('tanggal_invoice', '<=', $request->tgl_invoice_sampai);
        }

        if ($request->filled('tgl_jatuh_tempo_dari') && $request->filled('tgl_jatuh_tempo_sampai')) {
            $query->whereBetween('tanggal_jatuh_tempo', [
                $request->tgl_jatuh_tempo_dari,
                $request->tgl_jatuh_tempo_sampai
            ]);
        } elseif ($request->filled('tgl_jatuh_tempo_dari')) {
            $query->whereDate('tanggal_jatuh_tempo', '>=', $request->tgl_jatuh_tempo_dari);
        } elseif ($request->filled('tgl_jatuh_tempo_sampai')) {
            $query->whereDate('tanggal_jatuh_tempo', '<=', $request->tgl_jatuh_tempo_sampai);
        }

        return $query->orderBy("created_at", "DESC")->get();
    }

    public function insert_data(array $data)
    {
        $pembelian = Pembelian::create([
            "no_invoice" => $data['no_invoice'],
            "tanggal_invoice" => $data['tanggal_invoice'],
            "tanggal_jatuh_tempo" => $data["tanggal_jatuh_tempo"] ?? null,
            "total_harga" => $data["total_harga"],
            "total_ppn" => $data["total_ppn"],
            "total_qty" => $data["total_qty"],
            "supplier_id" => $data["supplier_id"],
            "keterangan" => $data["keterangan"],
            "created_by" => Auth::user()->id,
            "divisi_id" => AuthDivisi::id()
        ]);

        foreach ($data["items"] as $item) {
            DetailPembelian::create([
                "pembelian_id" => $pembelian->id,
                "sku_barang" => $item["barang_id"],
                "qty" => $item["qty"],
                "satuan" => $item["satuan"],
                "harga_satuan" => $item["harga_satuan"],
                "diskon" => $item["diskon"] ?? 0,
                "total_sebelum_ppn" => $item["total_sebelum_ppn"] ?? 0,
                "ppn" => $item["rate_ppn"] ?? 0,
                "total" => $item["total_sesudah_ppn"],
                "keterangan" => $item["keterangan"] ?? null,
            ]);

            Barang::where("id", $item["barang_id"])->update([
                "harga_modal"               => $item["harga_satuan"],
                "tanggal_pembelian_terakhir" => $data["tanggal_invoice"],
                "harga_pembelian_terakhir" => $item["harga_satuan"]
            ]);
        }

        return $pembelian;
    }

    public function get_data_by_id(string $id)
    {
        return Pembelian::where("id", $id)
            ->with("details")
            ->first();
    }

    public function update_by_id(string $id, array $header, array $items)
    {
        $pembelian = Pembelian::findOrFail($id);

        $pembelian->update($header);

        $existingDetailIds = [];

        foreach ($items as $item) {
            if (!empty($item['id'])) {

                DetailPembelian::where('id', $item['id'])
                    ->where('pembelian_id', $id)
                    ->update([
                        'sku_barang'   => $item['barang_id'],
                        'qty'          => $item['qty'],
                        'satuan'       => $item['satuan'],
                        'harga_satuan' => $item['harga_satuan'],
                        'diskon'       => $item['diskon'] ?? 0,
                        'ppn'          => $item['ppn'] ?? 0,
                        'total'        => $item['total_sesudah_ppn'],
                        'keterangan'   => $item['keterangan'] ?? null
                    ]);

                Barang::where("id", $item["barang_id"])->update([
                    "harga_modal"               => $item["harga_satuan"],
                    "tanggal_pembelian_terakhir" => $pembelian["tanggal_invoice"],
                    "harga_pembelian_terakhir" => $item["harga_satuan"]
                ]);

                $existingDetailIds[] = $item['id'];
            } else {
                $detail = DetailPembelian::create([
                    'pembelian_id' => $id,
                    'sku_barang'   => $item['barang_id'],
                    'qty'          => $item['qty'],
                    'satuan'       => $item['satuan'],
                    'harga_satuan' => $item['harga_satuan'],
                    'diskon'       => $item['diskon'] ?? 0,
                    'ppn'          => $item['ppn'] ?? 0,
                    'total'        => $item['total_sesudah_ppn'],
                    'keterangan'   => $item['keterangan'] ?? null,
                    'divisi_id'    => AuthDivisi::id()
                ]);

                Barang::where("id", $item["barang_id"])->update([
                    "harga_modal"               => $item["harga_satuan"],
                    "tanggal_pembelian_terakhir" => $pembelian["tanggal_invoice"],
                    "harga_pembelian_terakhir" => $item["harga_satuan"]
                ]);

                $existingDetailIds[] = $detail->id;
            }
        }

        DetailPembelian::where('pembelian_id', $id)
            ->whereNotIn('id', $existingDetailIds)
            ->delete();

        return $pembelian;
    }

    public function delete_by_id(string $id): void
    {
        $pembelian = Pembelian::findOrFail($id);
        DetailPembelian::where("pembelian_id", $id)->delete();
        $pembelian->delete();
    }
}
