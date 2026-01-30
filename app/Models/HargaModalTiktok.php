<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class HargaModalTiktok extends Model
{
    use HasUuids;

    protected $table = "harga_modal_tiktok";

    protected $guarded = [''];

    protected $keyType = "string";

    public $primaryKey = "id";

    public $incrementing = false;
}
