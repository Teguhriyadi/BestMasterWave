<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class InvoiceDataPesanan extends Model
{
    protected $table = "invoice_data_pesanan";

    protected $guarded = [''];

    public $primaryKey = "id";

    protected $casts = ["payload" => 'array'];

    public function file()
    {
        return $this->belongsTo(
            InvoiceFilePesanan::class,
            'invoice_file_pesanan_id'
        );
    }
}
