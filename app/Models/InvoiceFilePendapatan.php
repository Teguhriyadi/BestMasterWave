<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class InvoiceFilePendapatan extends Model
{
    use HasUuids;

    protected $table = "invoice_file_pendapatan";

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
        return $this->belongsTo(InvoiceSchemaPendapatan::class);
    }

    public function chunks()
    {
        return $this->hasMany(
            InvoiceDataPendapatan::class,
            'invoice_file_pendapatan_id'
        );
    }
}
