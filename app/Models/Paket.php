<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Paket extends Model
{
    use HasUuids;

    protected $table = "paket";

    protected $guarded = [''];

    protected $keyType = "string";

    public $primaryKey = "id";

    public function items()
    {
        return $this->hasMany(PaketItems::class, "paket_id");
    }
}
