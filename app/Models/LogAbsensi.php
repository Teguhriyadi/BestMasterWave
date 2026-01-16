<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class LogAbsensi extends Model
{
    use HasUuids;

    protected $table = "log_absensi";

    protected $guarded = [];

    public $incrementing = false;

    protected $keyType = "string";

    public $primaryKey = "id";

    protected $casts = [
        'tanggal_waktu' => 'datetime',
    ];

    public function divisi()
    {
        return $this->belongsTo(Divisi::class, "divisi_id");
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, "kode_lokasi", "kode_lokasi");
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, "id_fp", "id_fp");
    }
}
