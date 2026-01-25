<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceDataTiktokPesanan extends Model
{
    protected $table = "invoice_data_tiktok_pesanan";

    protected $guarded = [''];

    public $primaryKey = "id";

    protected $casts = ["payload" => 'array'];

    public function file()
    {
        return $this->belongsTo(
            InvoiceFileTiktokPesanan::class,
            'invoice_file_pesanan_id'
        );
    }
}
