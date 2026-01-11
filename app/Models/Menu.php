<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasUuids;

    protected $table = "menu";

    protected $guarded = [""];

    public $primaryKey = "id";

    protected $keyType = "string";

    public $incrementing = false;

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class, 'menu_id');
    }
}
