<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class DendaKaryawan extends Model
{
    use HasUuids;

    protected $table = "denda_karyawan";

    protected $guarded = [''];

    protected $keyType = "string";

    public $primaryKey = "id";

    protected $casts = [
        'tanggal_denda' => 'date',
        'periode_gaji' => 'date'
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, "karyawan_id");
    }

    public function jenis_denda()
    {
        return $this->belongsTo(JenisDenda::class, "jenis_denda_id");
    }
}
