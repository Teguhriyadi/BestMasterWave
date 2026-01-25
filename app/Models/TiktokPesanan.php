<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TiktokPesanan extends Model
{
    protected $table = "tiktok_pesanan";

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
