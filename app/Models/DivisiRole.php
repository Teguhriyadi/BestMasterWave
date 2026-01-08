<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class DivisiRole extends Model
{
    use HasUuids;

    protected $table = "divisi_role";

    protected $guarded = [''];

    protected $keyType = "string";

    public $primaryKey = "id";

    public function roles()
    {
        return $this->belongsToMany(
            Role::class,
            'divisi_role',
            'divisi_id',
            'role_id'
        );
    }

    public function users()
    {
        return $this->belongsTo(User::class, "user_id");
    }
}
