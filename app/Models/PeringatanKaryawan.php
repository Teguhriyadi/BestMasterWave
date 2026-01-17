<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class PeringatanKaryawan extends Model
{
    use HasUuids;

    protected $table = "peringatan_karyawan";

    protected $guarded = [''];

    protected $keyType = "string";

    public $primaryKey = "id";

    protected $casts = [
        "tanggal_pelanggaran" => 'date',
        "tanggal_terbit_sp" => 'date',
        "berlaku_sampai" => 'date'
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, "karyawan_id");
    }

    public function jenis_peringatan()
    {
        return $this->belongsTo(JenisPeringatan::class, "jenis_peringatan_id");
    }
}
