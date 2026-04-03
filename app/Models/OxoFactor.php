<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OxoFactor extends Model
{
    protected $fillable = [
        'value',
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'value' => 'integer',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'oxo_factor', 'value');
    }
}
