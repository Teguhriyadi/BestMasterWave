<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class InvoiceDataPendapatan extends Model
{
    protected $table = "invoice_data_pendapatan";

    protected $guarded = [''];

    public $primaryKey = "id";

    protected $casts = ["payload" => 'array'];

    public function file()
    {
        return $this->belongsTo(
            InvoiceFilePendapatan::class,
            'invoice_file_pendapatan_id'
        );
    }
}
