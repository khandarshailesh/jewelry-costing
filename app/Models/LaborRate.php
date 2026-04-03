<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class LaborRate extends Model
{
    protected $fillable = [
        'process_name',
        'sub_type',
        'size',
        'material_cost',
        'labor_cost',
        'is_active',
    ];

    protected $casts = [
        'material_cost' => 'decimal:4',
        'labor_cost' => 'decimal:4',
        'is_active' => 'boolean',
    ];

    protected $appends = ['total_cost'];

    protected function totalCost(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->material_cost + $this->labor_cost,
        );
    }

    /**
     * Find labor rate by process, sub_type, and size
     */
    public static function findRate(string $process, ?string $subType = null, ?string $size = null): ?self
    {
        return static::where('process_name', $process)
            ->where('sub_type', $subType)
            ->where('size', $size)
            ->where('is_active', true)
            ->first();
    }
}
