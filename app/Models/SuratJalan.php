<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class SuratJalan extends Model
{
    use HasUuids;

    protected $table = "surat_jalan";

    protected $guarded = [''];

    protected $keyType = "string";

    public $primaryKey = "id";
}
