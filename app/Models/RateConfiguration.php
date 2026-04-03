<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class RateConfiguration extends Model
{
    protected $fillable = [
        'name',
        'code',
        'rate_value',
        'rate_type',
        'category_id',
        'description',
        'is_active',
        'effective_from',
        'effective_to',
    ];

    protected $casts = [
        'rate_value' => 'decimal:4',
        'is_active' => 'boolean',
        'effective_from' => 'date',
        'effective_to' => 'date',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get active rate value by code
     */
    public static function getActiveRate(string $code, ?float $default = null): ?float
    {
        return Cache::remember("rate_{$code}", 3600, function () use ($code, $default) {
            $rate = static::where('code', $code)
                ->where('is_active', true)
                ->where('effective_from', '<=', now())
                ->where(function ($query) {
                    $query->whereNull('effective_to')
                          ->orWhere('effective_to', '>=', now());
                })
                ->first();

            return $rate?->rate_value ?? $default;
        });
    }

    /**
     * Get all active rates as associative array
     */
    public static function getAllActiveRates(): array
    {
        return Cache::remember('all_active_rates', 3600, function () {
            return static::where('is_active', true)
                ->where('effective_from', '<=', now())
                ->where(function ($query) {
                    $query->whereNull('effective_to')
                          ->orWhere('effective_to', '>=', now());
                })
                ->pluck('rate_value', 'code')
                ->toArray();
        });
    }

    /**
     * Clear rate cache when updated
     */
    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget('all_active_rates');
        });

        static::deleted(function () {
            Cache::forget('all_active_rates');
        });
    }
}
