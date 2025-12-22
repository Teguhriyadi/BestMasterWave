<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    use HasUuids;

    protected $table = "seller";

    protected $guarded = [''];

    protected $keyType = "string";

    public $primaryKey = "id";

    public function platform()
    {
        return $this->belongsTo(Platform::class, "platform_id", "id");
    }
}
