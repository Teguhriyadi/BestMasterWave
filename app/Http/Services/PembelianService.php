<?php

namespace App\Http\Services;

use App\Helpers\AuthDivisi;
use App\Http\Mapper\PembelianMapper;
use App\Http\Mapper\SupplierMapper;
use App\Http\Repositories\PembelianRepository;
use App\Http\Repositories\SupplierRepository;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PembelianService
{
    public function __construct(
        protected PembelianRepository $pembelian_repository
    ) {}

    public function list()
    {
        $supplier = $this->pembelian_repository->get_all_data();

        return PembelianMapper::toTable($supplier);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $totalHarga  = 0;
            $totalDiskon = 0;
            $totalPPN    = 0;
            $totalQty    = 0;

            foreach ($data['items'] as $item) {
                $subtotal = $item['qty'] * $item['harga_satuan'];

                $totalHarga  += $subtotal;
                $totalDiskon += $item['diskon'] ?? 0;
                $totalPPN    += $item['ppn'] ?? 0;
                $totalQty    += $item['qty'];
            }

            return $this->pembelian_repository->insert_data([
                ...$data,
                'total_harga'  => $totalHarga,
                'total_diskon' => $totalDiskon,
                'total_ppn'    => $totalPPN,
                'total_qty'    => $totalQty,
            ]);
        });
    }

    public function edit(string $id)
    {
        return DB::transaction(function() use ($id) {
            return $this->pembelian_repository->get_data_by_id($id);
        });
    }

    public function update(string $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {

            $totalHarga = 0;
            $totalPPN   = 0;
            $totalQty   = 0;

            $items = $data['items'] ?? [];

            foreach ($items as $item) {
                $subtotal = $item['qty'] * $item['harga_satuan'];

                $totalHarga += $subtotal;
                $totalPPN   += $item['ppn'] ?? 0;
                $totalQty  += $item['qty'];
            }

            return $this->pembelian_repository->update_by_id(
                $id,
                [
                    'no_invoice'           => $data['no_invoice'],
                    'supplier_id'          => $data['supplier_id'],
                    'tanggal_invoice'      => $data['tanggal_invoice'],
                    'tanggal_jatuh_tempo'  => $data['tanggal_jatuh_tempo'] ?? null,
                    'total_harga'          => $totalHarga,
                    'total_ppn'            => $totalPPN,
                    'total_qty'            => $totalQty,
                    'updated_by'           => Auth::user()->id,
                    'divisi_id'            => AuthDivisi::id(),
                    'keterangan'           => $data['keterangan'],
                ],
                $items
            );
        });
    }

    public function delete(string $id)
    {
        return DB::transaction(function () use ($id) {
            $this->pembelian_repository->delete_by_id($id);
        });
    }
}
