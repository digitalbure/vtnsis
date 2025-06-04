<?php

namespace Modules\Shop\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class MerchProduct extends Product
{
    protected $fillable = [
        'size',
        'color',
        'inventory',
        'is_merch',
    ];

    protected $casts = [
        'is_merch' => 'boolean',
    ];
} 