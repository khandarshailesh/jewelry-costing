<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LuckyItem extends Model
{
    protected $fillable = [
        'item_number',
        'weight',
        'mino_count',
        'diamond_cost',
        'packing_cost',
        'total_cost',
        'markup_1',
        'markup_2',
        'price_after_markup1',
        'final_price',
        'is_active',
    ];

    protected $casts = [
        'weight' => 'decimal:4',
        'mino_count' => 'decimal:2',
        'diamond_cost' => 'decimal:4',
        'packing_cost' => 'decimal:4',
        'total_cost' => 'decimal:4',
        'markup_1' => 'decimal:2',
        'markup_2' => 'decimal:2',
        'price_after_markup1' => 'decimal:4',
        'final_price' => 'decimal:4',
        'is_active' => 'boolean',
    ];
}
