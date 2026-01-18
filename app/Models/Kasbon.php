<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Kasbon extends Model
{
    use HasUuids;

    protected $table = "kasbon";

    protected $guarded = [];

    public $incrementing = false;

    protected $keyType = "string";

    public $primaryKey = "id";

    protected $casts = [
        'tanggal_mulai' => 'date'
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, "karyawan_id");
    }

    public function transaksi()
    {
        return $this->hasMany(KasbonTransaksi::class, "kasbon_id", "id");
    }
}
