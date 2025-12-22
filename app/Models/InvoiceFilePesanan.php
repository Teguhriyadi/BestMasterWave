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
}
