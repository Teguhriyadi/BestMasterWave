<?php

namespace App\Http\Services;

use App\Http\Mapper\SellerMapper;
use App\Http\Mapper\SupplierMapper;
use App\Http\Repositories\SellerRepository;

class SellerService
{
    public function __construct(
        protected SellerRepository $seller_repository
    ) {}

    public function list()
    {
        $supplier = $this->seller_repository->get_list_data();

        return SellerMapper::toListSelectOption($supplier);
    }
}
