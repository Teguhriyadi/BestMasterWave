<?php

namespace App\Http\Mapper;

use App\Models\Bank;
use Illuminate\Support\Collection;

class BankMapper
{
    public static function toTable(Collection $supplier): Collection
    {
        return $supplier->map(function(Bank $supplier) {
            return [
                'id'        => $supplier->id,
                'nama_bank' => $supplier->nama_bank,
                'alias'     => $supplier->alias,
                'slug_bank' => $supplier->slug_bank,
                'aktif' => $supplier->is_active == "1" ? "Aktif" : "Tidak Aktif"
            ];
        });
    }
}
