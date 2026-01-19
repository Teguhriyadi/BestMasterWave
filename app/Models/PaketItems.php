<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class PaketItems extends Model
{
    use HasUuids;

    protected $table = "paket_items";

    protected $guarded = [''];

    protected $keyType = "string";

    public $primaryKey = "id";

    public function barangs()
    {
        return $this->belongsTo(Barang::class, "barang_id");
    }
}
