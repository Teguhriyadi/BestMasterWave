<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasUuids;

    protected $table = "pembelian";

    protected $guarded = [''];

    protected $keyType = "string";

    public $primaryKey = "id";

    protected $casts = [
        "tanggal_invoice" => 'date',
        "tanggal_jatuh_tempo" => 'date'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, "supplier_id");
    }

    public function details()
    {
        return $this->hasMany(DetailPembelian::class, "pembelian_id", "id");
    }

    public function divisi()
    {
        return $this->belongsTo(Divisi::class, "divisi_id")
            ->withDefault([
                "nama_divisi" => "-"
            ]);
    }
}
