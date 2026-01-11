<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    use HasUuids;

    protected $table = "role_permission";

    protected $guarded = [""];

    public $primaryKey = "id";

    protected $keyType = "string";
}
