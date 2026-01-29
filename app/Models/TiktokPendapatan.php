<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TiktokPendapatan extends Model
{
    protected $table = "tiktok_pendapatan";

    protected $guarded = [''];

    protected static function booted()
    {
        static::creating(function ($model) {

            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }

        });
    }

    public function pesanan()
    {
        return $this->hasMany(
            TiktokPesanan::class,
            'order_id',
            'order_or_adjustment_id'
        );
    }
}
