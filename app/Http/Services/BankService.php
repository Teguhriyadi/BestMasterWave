<?php

namespace App\Http\Services;

use App\Http\Mapper\BankMapper;
use App\Http\Repositories\BankRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BankService
{
    public function __construct(
        protected BankRepository $bank_repository
    ) {}

    public function list()
    {
        $supplier = $this->bank_repository->get_all_data();

        return BankMapper::toTable($supplier);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            return $this->bank_repository->insert_data($data);
        });
    }

    public function edit(string $id)
    {
        return DB::transaction(function() use ($id) {
            return $this->bank_repository->get_data_by_id($id);
        });
    }

    public function update(string $id, array $data)
    {
        $data['slug_bank'] = Str::slug($data['nama_bank']);

        return DB::transaction(function () use ($id, $data) {
            return $this->bank_repository->update_by_id($id, $data);
        });
    }

    public function delete(string $id)
    {
        return DB::transaction(function () use ($id) {
            $this->bank_repository->delete_by_id($id);
        });
    }
}
