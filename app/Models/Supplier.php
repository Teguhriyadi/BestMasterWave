<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasUuids;

    protected $table = "supplier";

    protected $guarded = [''];

    protected $keyType = "string";

    public $primaryKey = "id";

    public function bank()
    {
        return $this->belongsTo(Bank::class, "bank_id");
    }

    public function divisi()
    {
        return $this->belongsTo(Divisi::class, "divisi_id")
            ->withDefault([
                "nama_divisi" => "-"
            ]);
    }
}
