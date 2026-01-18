<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class SetupJamKerja extends Model
{
    use HasUuids;

    protected $table = "setup_jam_kerja";

    protected $guarded = [''];

    protected $keyType = "string";

    public $primaryKey = "id";

    public function divisi()
    {
        return $this->belongsTo(Divisi::class, "divisi_id");
    }
}
