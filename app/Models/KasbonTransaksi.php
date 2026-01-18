<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class KasbonTransaksi extends Model
{
    use HasUuids;

    protected $table = "kasbon_transaksi";

    protected $guarded = [];

    public $incrementing = false;

    protected $keyType = "string";

    public $primaryKey = "id";
}
