<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasUuids;

    protected $table = "barang";

    protected $guarded = [''];

    protected $keyType = "string";

    public $primaryKey = "id";

    public function seller()
    {
        return $this->belongsTo(Seller::class, "seller_id");
    }

    public function divisi()
    {
        return $this->belongsTo(Divisi::class, "divisi_id")
            ->withDefault([
                "nama_divisi" => "-"
            ]);
    }
}
