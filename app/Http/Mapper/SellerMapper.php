<?php

namespace App\Http\Mapper;

use App\Models\Seller;
use Illuminate\Support\Collection;

class SellerMapper
{
    public static function toListSelectOption(Collection $supplier): Collection
    {
        return $supplier->map(function(Seller $supplier) {
            return [
                'id' => $supplier->id,
                'nama' => $supplier->nama
            ];
        });
    }
}
