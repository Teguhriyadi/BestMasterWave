<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class HargaModal extends Model
{
    use HasUuids;

    protected $table = "harga_modal";

    protected $guarded = [''];

    protected $keyType = "string";

    public $primaryKey = "id";

    public $incrementing = false;
}
