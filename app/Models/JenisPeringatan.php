<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class JenisPeringatan extends Model
{
    use HasUuids;

    protected $table = "jenis_peringatan";

    protected $guarded = [''];

    protected $keyType = "string";

    public $primaryKey = "id";
}
