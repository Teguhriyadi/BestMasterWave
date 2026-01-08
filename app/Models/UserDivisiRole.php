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

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function roles()
    {
        return $this->belongsTo(Role::class, "role_id");
    }

    public function divisi()
    {
        return $this->belongsTo(Divisi::class, "divisi_id");
    }
}
