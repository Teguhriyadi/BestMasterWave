<?php

namespace App\Http\Services;

use App\Http\Mapper\SellerMapper;
use App\Http\Repositories\SellerRepository;
use Illuminate\Support\Facades\DB;

class SellerService
{
    public function __construct(
        protected SellerRepository $seller_repository
    ) {}

    public function list()
    {
        $seller = $this->seller_repository->get_all_data();

        return SellerMapper::toTable($seller);
    }

    public function list_seller()
    {
        $seller = $this->seller_repository->list_data_seller();

        return SellerMapper::toListOption($seller);
    }

    public function list_seller_all()
    {
        $seller = $this->seller_repository->list_data_seller_all();

        return SellerMapper::toListOption($seller);
    }

    public function list_seller_tiktok()
    {
        $seller = $this->seller_repository->list_data_seller_tiktok();

        return SellerMapper::toListOption($seller);
    }

    public function list_seller_by_divisi()
    {
        $seller = $this->seller_repository->list_data_seller_by_id();

        return SellerMapper::toListOption($seller);
    }

    public function list_seller_shopee_divisi()
    {
        $seller = $this->seller_repository->list_seller_shopee();

        return SellerMapper::toListOption($seller);
    }

    public function list_seller_tiktok_divisi()
    {
        $seller = $this->seller_repository->list_seller_tiktok();

        return SellerMapper::toListOption($seller);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            return $this->seller_repository->insert_data($data);
        });
    }

    public function edit(string $id)
    {
        return DB::transaction(function() use ($id) {
            return $this->seller_repository->get_data_by_id($id);
        });
    }

    public function update(string $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            return $this->seller_repository->update_by_id($id, $data);
        });
    }

    public function delete(string $id)
    {
        return DB::transaction(function () use ($id) {
            $this->seller_repository->delete_by_id($id);
        });
    }
}
