<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasUuids;

    protected $table = "permissions";

    protected $guarded = [""];

    public $primaryKey = "id";

    protected $keyType = "string";

    public function menu()
    {
        return $this->belongsTo(Menu::class, "menu_id");
    }
}
