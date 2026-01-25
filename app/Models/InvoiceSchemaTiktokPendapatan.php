<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class InvoiceSchemaTiktokPendapatan extends Model
{
    use HasUuids;

    protected $table = "invoice_schema_tiktok_pendapatan";

    protected $guarded = [''];

    protected $keyType = "string";

    public $primaryKey = "id";

    protected $casts = [
        'columns_mapping' => 'array'
    ];

    public function files()
    {
        return $this->hasMany(
            InvoiceFileTiktokPendapatan::class,
            'schema_id'
        );
    }
}
