<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class InvoiceSchemaPendapatan extends Model
{
    use HasUuids;

    protected $table = "invoice_schema_pendapatan";

    protected $guarded = [''];

    protected $keyType = "string";

    public $primaryKey = "id";

    public $timestamps = false;

    protected $casts = [
        'headers' => 'array'
    ];
}
