<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ShopeePesanan extends Model
{
    protected $table = "shopee_pesanan";

    protected $guarded = [''];

    protected static function booted()
    {
        static::creating(function ($model) {

            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }

        });
    }
}
