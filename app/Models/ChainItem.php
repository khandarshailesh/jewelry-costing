<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChainItem extends Model
{
    protected $fillable = [
        'item_number',
        'weight',
        'base_rate',
        'making_charge',
        'pcs_rate',
        'markup_1',
        'markup_2',
        'is_active',
    ];

    protected $casts = [
        'weight' => 'decimal:4',
        'base_rate' => 'decimal:2',
        'making_charge' => 'decimal:2',
        'pcs_rate' => 'decimal:2',
        'markup_1' => 'decimal:2',
        'markup_2' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}
