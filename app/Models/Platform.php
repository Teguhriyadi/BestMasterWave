<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Platform extends Model
{
    use HasUuids;

    protected $table = "platform";

    protected $guarded = [''];

    protected $keyType = "string";

    public $primaryKey = "id";
}
