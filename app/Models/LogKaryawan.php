<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class LogKaryawan extends Model
{
    use HasUuids;

    protected $table = "log_karyawan";

    protected $guarded = [];

    public $incrementing = false;

    public $primaryKey = "id";

    protected $keyType = "string";

    public function users()
    {
        return $this->belongsTo(User::class, "created_by", "id");
    }
}
