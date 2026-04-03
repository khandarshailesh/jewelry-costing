<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    protected $fillable = [
        'product_number',
        'name',
        'weight',
        'mino_count',
        'stone_count',
        'chips_count',
        'oxo_factor',
        'stone_type',
        'category_id',
        'is_active',
    ];

    protected $casts = [
        'weight' => 'decimal:4',
        'oxo_factor' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected $appends = ['latest_price'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function costings(): HasMany
    {
        return $this->hasMany(ProductCosting::class);
    }

    public function latestCosting()
    {
        return $this->hasOne(ProductCosting::class)->latestOfMany();
    }

    public function getLatestPriceAttribute(): ?float
    {
        return $this->latestCosting?->final_price;
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (!$search) return $query;

        return $query->where('product_number', 'like', "%{$search}%")
                     ->orWhere('name', 'like', "%{$search}%");
    }
}
