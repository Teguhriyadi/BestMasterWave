<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class UserDivisiRole extends Model
{
    use HasUuids;

    protected $table = "users_divisi_role";

    protected $guarded = [''];

    protected $keyType = "string";

    public $primaryKey = "id";

    public $incrementing = false;
}
