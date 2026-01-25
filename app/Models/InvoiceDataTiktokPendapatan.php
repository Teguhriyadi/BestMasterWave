<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceDataTiktokPendapatan extends Model
{
    protected $table = "invoice_data_tiktok_pendapatan";

    protected $guarded = [''];

    public $primaryKey = "id";

    protected $casts = ["payload" => 'array'];

    public function file()
    {
        return $this->belongsTo(
            InvoiceFileTiktokPendapatan::class,
            'invoice_file_pendapatan_id'
        );
    }
}
