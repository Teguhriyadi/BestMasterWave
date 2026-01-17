<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasUuids;

    protected $table = "karyawan";

    protected $guarded = [];

    public $incrementing = false;

    protected $keyType = "string";

    public $primaryKey = "id";

    protected $casts = [
        "tanggal_masuk" => 'date',
        "tanggal_lahir" => 'date'
    ];

    public function divisi()
    {
        return $this->belongsTo(Divisi::class, "divisi_id")
            ->withDefault([
                "nama_divisi" => "-"
            ]);
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, "jabatan_id");
    }
}
