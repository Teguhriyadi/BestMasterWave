<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasUuids;

    protected $guarded = [''];

    protected $keyType = "string";

    public $primaryKey = "id";

    public $incrementing = false;

    public function divisiRoles()
    {
        return $this->hasMany(UserDivisiRole::class,'user_id','id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class,'users_divisi_role','user_id','role_id');
    }

    public function divisi()
    {
        return $this->belongsToMany(Divisi::class,'users_divisi_role','user_id','divisi_id');
    }

    public function one_divisi_roles()
    {
        return $this->hasOne(UserDivisiRole::class, "user_id", "id");
    }
}
