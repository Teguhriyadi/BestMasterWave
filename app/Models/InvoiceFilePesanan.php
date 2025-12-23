<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class InvoiceFilePesanan extends Model
{
    use HasUuids;

    protected $table = "invoice_file_pesanan";

    protected $guarded = [''];

    protected $keyType = "string";

    public $primaryKey = "id";

    public $timestamps = false;

    public function seller()
    {
        return $this->belongsTo(Seller::class, "seller_id");
    }

    public function platform()
    {
        return $this->seller->platform();
    }

    public function schema()
    {
        return $this->belongsTo(InvoiceSchemaPesanan::class);
    }

    public function chunks()
    {
        return $this->hasMany(
            InvoiceDataPesanan::class,
            'invoice_file_pesanan_id'
        );
    }
}
