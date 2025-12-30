<?php

namespace App\Http\Repositories;

use App\Models\Seller;

class SellerRepository
{
    public function get_list_data()
    {
        return Seller::orderBy("created_at", "DESC")->get();
    }
}
