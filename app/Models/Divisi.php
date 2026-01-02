<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Divisi extends Model
{
    use HasUuids;

    protected $table = "divisi";

    protected $guarded = [''];

    protected $keyType = "string";

    public $primaryKey = "id";
}
