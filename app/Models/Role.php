<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasUuids;

    protected $table = "role";

    protected $guarded = [""];

    public $incrementing = false;

    protected $keyType = "string";

    public $primaryKey = "id";

    public function rolePermissions()
    {
        return $this->hasMany(RolePermission::class, 'role_id');
    }

    public function divisi()
    {
        return $this->belongsTo(Divisi::class, "divisi_id")
            ->withDefault([
                "nama_divisi" => "-"
            ]);
    }

    public function divisions()
    {
        return $this->belongsToMany(
            Divisi::class,
            'divisi_role',
            'role_id',
            'divisi_id'
        )->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', '1');
    }

    public function scopeSuperAdmin($query)
    {
        return $query->where('nama_role', 'Super Admin');
    }

    public function scopeExceptSuperAdmin($query)
    {
        return $query->where('nama_role', '!=', 'Super Admin');
    }
}
