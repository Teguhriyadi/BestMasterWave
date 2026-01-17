<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class RekapAbsensi extends Model
{
    use HasUuids;

    protected $table = "rekap_absensi";

    protected $guarded = [''];

    protected $keyType = "string";

    public $primaryKey = "id";

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, "karyawan_id");
    }
}
