<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductMaster extends Model
{
    protected $table = 'products_master';
    protected $guarded = [];

    protected $casts = [
        'id' => 'string'
    ];
}
