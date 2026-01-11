<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class DetailPembelian extends Model
{
    use HasUuids;

    protected $table = "detail_pembelian";

    protected $guarded = [''];

    protected $keyType = "string";

    public $primaryKey = "id";

    public function barang()
    {
        return $this->belongsTo(Barang::class, "sku_barang", "id");
    }
}
