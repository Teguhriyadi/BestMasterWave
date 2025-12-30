<?php

namespace App\Http\Services;

use App\Http\Mapper\SupplierMapper;
use App\Http\Repositories\SupplierRepository;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;

class SupplierService
{
    public function __construct(
        protected SupplierRepository $supplier_repository
    ) {}

    public function list()
    {
        $supplier = $this->supplier_repository->get_all_data();

        return SupplierMapper::toTable($supplier);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            return $this->supplier_repository->insert_data($data);
        });
    }

    public function edit(string $id)
    {
        return DB::transaction(function() use ($id) {
            return $this->supplier_repository->get_data_by_id($id);
        });
    }

    public function update(string $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            return $this->supplier_repository->update_by_id($id, $data);
        });
    }

    public function delete(string $id)
    {
        return DB::transaction(function () use ($id) {
            $this->supplier_repository->delete_by_id($id);
        });
    }
}
