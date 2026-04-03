<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoneType extends Model
{
    protected $fillable = [
        'code',
        'name',
        'price_per_unit',
        'description',
        'is_active',
    ];

    protected $casts = [
        'price_per_unit' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'stone_type', 'code');
    }
}
