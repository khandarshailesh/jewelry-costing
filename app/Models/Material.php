<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Material extends Model
{
    protected $fillable = [
        'material_number',
        'name',
        'weight',
        'price',
        'unit',
        'category',
        'is_active',
    ];

    protected $casts = [
        'weight' => 'decimal:4',
        'price' => 'decimal:4',
        'is_active' => 'boolean',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (!$search) return $query;

        return $query->where('material_number', 'like', "%{$search}%")
                     ->orWhere('name', 'like', "%{$search}%");
    }

    /**
     * VLOOKUP equivalent - find material by number
     */
    public static function lookup(string $materialNumber): ?self
    {
        return static::where('material_number', $materialNumber)->first();
    }
}
