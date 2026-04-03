<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductCosting extends Model
{
    protected $fillable = [
        'product_id',
        'weight',
        'mino_count',
        'stone_count',
        'chips_count',
        'chips_cost',
        'oxo_factor',
        'metal_rate',
        'stone_rate',
        'mino_rate',
        'wastage_percentage',
        'markup_1',
        'markup_2',
        'metal_cost',
        'stone_cost',
        'oxo_cost',
        'mino_cost',
        'gross_total',
        'wastage_amount',
        'total_with_wastage',
        'price_after_markup1',
        'final_price',
        'costing_date',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'weight' => 'decimal:4',
        'oxo_factor' => 'decimal:2',
        'metal_rate' => 'decimal:4',
        'stone_rate' => 'decimal:4',
        'mino_rate' => 'decimal:4',
        'wastage_percentage' => 'decimal:4',
        'markup_1' => 'decimal:2',
        'markup_2' => 'decimal:2',
        'metal_cost' => 'decimal:4',
        'stone_cost' => 'decimal:4',
        'oxo_cost' => 'decimal:4',
        'mino_cost' => 'decimal:4',
        'gross_total' => 'decimal:4',
        'wastage_amount' => 'decimal:4',
        'total_with_wastage' => 'decimal:4',
        'price_after_markup1' => 'decimal:4',
        'final_price' => 'decimal:4',
        'costing_date' => 'date',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
