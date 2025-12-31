<?php

namespace App\Http\Services;

use App\Http\Mapper\BarangMapper;
use App\Http\Mapper\SupplierMapper;
use App\Http\Repositories\BarangRepository;
use App\Http\Repositories\SupplierRepository;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;

class BarangService
{
    public function __construct(
        protected SupplierRepository $supplier_repository,
        protected BarangRepository $barang_repository
    ) {}

    public function list()
    {
        $barang = $this->barang_repository->get_all_data();

        return BarangMapper::toTable($barang);
    }

    public function list_barang_sku()
    {
        $barang = $this->barang_repository->get_all_data();

        return BarangMapper::toSkuTable($barang);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            return $this->barang_repository->insert_data($data);
        });
    }

    public function edit(string $id)
    {
        return DB::transaction(function() use ($id) {
            return $this->barang_repository->get_data_by_id($id);
        });
    }

    public function update(string $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            return $this->barang_repository->update_by_id($id, $data);
        });
    }

    public function delete(string $id)
    {
        return DB::transaction(function () use ($id) {
            $this->barang_repository->delete_by_id($id);
        });
    }
}
