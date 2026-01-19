<?php

namespace App\Http\Services;

use App\Http\Mapper\PaketMapper;
use App\Http\Repositories\PaketRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaketService
{
    public function __construct(
        protected PaketRepository $paket_repository
    ) {}

    public function list()
    {
        $pakets = $this->paket_repository->get_all_data();

        return $pakets->map(function($paket) {
            return PaketMapper::toViewModel($paket);
        });
    }

    public function create(array $data)
    {
        return DB::transaction(function() use ($data) {
            $paket = $this->paket_repository->insert_data($data);

            foreach ($data["barang_id"] as $key => $barangId) {
                $this->paket_repository->insertItem([
                    'paket_id' => $paket->id,
                    'barang_id' => $barangId,
                    'qty' => $data['qty'][$key],
                    'harga_satuan' => $data['harga'][$key]
                ]);
            }

            return $paket;
        });
    }

    public function edit(string $id)
    {
        return DB::transaction(function() use ($id) {
            return $this->paket_repository->get_data_by_id($id);
        });
    }

    public function update(string $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $this->paket_repository->update_header($id, [
                'sku_paket'  => $data['sku_paket'],
                'nama_paket' => $data['nama_paket'],
                'harga_jual' => $data['harga_paket'],
                'seller_id'  => $data['seller_id'],
                'updated_by' => Auth::user()->id
            ]);

            $this->paket_repository->delete_items_by_paket_id($id);

            $newItems = [];
            foreach ($data['barang_id'] as $key => $barangId) {
                $newItems[] = [
                    'id'            => Str::uuid(),
                    'paket_id'      => $id,
                    'barang_id'     => $barangId,
                    'qty'           => $data['qty'][$key],
                    'harga_satuan'  => $data['harga'][$key],
                ];
            }

            return $this->paket_repository->insert_items_batch($newItems);
        });
    }

    public function delete(string $id)
    {
        return DB::transaction(function () use ($id) {
            $this->paket_repository->delete_by_id($id);
        });
    }
}
