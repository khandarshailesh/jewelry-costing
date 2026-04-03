<?php

namespace App\Services;

use App\Models\Product;
use App\Models\RateConfiguration;
use App\Models\ProductCosting;

class CostingCalculationService
{
    /**
     * COSTING FORMULA (from Excel Casting sheet):
     *
     * G = Weight × Metal Rate (1.1)
     * H = Stone Rate × Stone Count (0.06/0.07/0.10 based on type)
     * I = (Weight × OXO Factor) / 10
     * J = MINO Rate × MINO Count (0.035)
     * K = Chips Cost (optional)
     * L = SUM(G:K) = Gross Total
     * M = Gross Total × Wastage % (0.03)
     * N = Gross Total + Wastage
     * O = N × Markup 1 (1.25)
     * P = O × Markup 2 (1.65) = Final Price
     */

    public function calculateProductCost(Product $product, ?array $customRates = null): array
    {
        $rates = $customRates ?? $this->getCurrentRates();

        // Get stone rate based on type (k=0.06, S=0.10, G/A=0.07)
        $stoneRate = $this->getStoneRateByType($product->stone_type, $rates);

        // Calculate component costs
        $metalCost = $product->weight * $rates['metal_rate'];
        $stoneCost = $stoneRate * $product->stone_count;
        $oxoCost = ($product->weight * $product->oxo_factor) / 10;
        $minoCost = $rates['mino_rate'] * $product->mino_count;
        $chipsCost = $product->chips_count * ($rates['chips_rate'] ?? 0.15);

        // Calculate totals
        $grossTotal = $metalCost + $stoneCost + $oxoCost + $minoCost + $chipsCost;
        $wastageAmount = $grossTotal * $rates['wastage_percentage'];
        $totalWithWastage = $grossTotal + $wastageAmount;
        $priceAfterMarkup1 = $totalWithWastage * $rates['markup_1'];
        $finalPrice = $priceAfterMarkup1 * $rates['markup_2'];

        return [
            // Inputs
            'weight' => $product->weight,
            'mino_count' => $product->mino_count,
            'stone_count' => $product->stone_count,
            'chips_count' => $product->chips_count,
            'oxo_factor' => $product->oxo_factor,

            // Rates used
            'metal_rate' => $rates['metal_rate'],
            'stone_rate' => $stoneRate,
            'mino_rate' => $rates['mino_rate'],
            'wastage_percentage' => $rates['wastage_percentage'],
            'markup_1' => $rates['markup_1'],
            'markup_2' => $rates['markup_2'],

            // Calculated costs
            'metal_cost' => round($metalCost, 4),
            'stone_cost' => round($stoneCost, 4),
            'oxo_cost' => round($oxoCost, 4),
            'mino_cost' => round($minoCost, 4),
            'chips_cost' => round($chipsCost, 4),
            'gross_total' => round($grossTotal, 4),
            'wastage_amount' => round($wastageAmount, 4),
            'total_with_wastage' => round($totalWithWastage, 4),
            'price_after_markup1' => round($priceAfterMarkup1, 4),
            'final_price' => round($finalPrice, 4),
        ];
    }

    public function calculateFromInputs(array $inputs, ?array $customRates = null): array
    {
        $rates = $customRates ?? $this->getCurrentRates();

        $weight = $inputs['weight'] ?? 0;
        $minoCount = $inputs['mino_count'] ?? 0;
        $stoneCount = $inputs['stone_count'] ?? 0;
        $chipsCount = $inputs['chips_count'] ?? 0;
        $oxoFactor = $inputs['oxo_factor'] ?? 0;
        $stoneType = $inputs['stone_type'] ?? 'k';

        $stoneRate = $this->getStoneRateByType($stoneType, $rates);

        $metalCost = $weight * $rates['metal_rate'];
        $stoneCost = $stoneRate * $stoneCount;
        $oxoCost = ($weight * $oxoFactor) / 10;
        $minoCost = $rates['mino_rate'] * $minoCount;
        $chipsCost = $chipsCount * ($rates['chips_rate'] ?? 0.15);

        $grossTotal = $metalCost + $stoneCost + $oxoCost + $minoCost + $chipsCost;
        $wastageAmount = $grossTotal * $rates['wastage_percentage'];
        $totalWithWastage = $grossTotal + $wastageAmount;
        $priceAfterMarkup1 = $totalWithWastage * $rates['markup_1'];
        $finalPrice = $priceAfterMarkup1 * $rates['markup_2'];

        return [
            'weight' => $weight,
            'mino_count' => $minoCount,
            'stone_count' => $stoneCount,
            'chips_count' => $chipsCount,
            'oxo_factor' => $oxoFactor,
            'metal_rate' => $rates['metal_rate'],
            'stone_rate' => $stoneRate,
            'mino_rate' => $rates['mino_rate'],
            'wastage_percentage' => $rates['wastage_percentage'],
            'markup_1' => $rates['markup_1'],
            'markup_2' => $rates['markup_2'],
            'metal_cost' => round($metalCost, 4),
            'stone_cost' => round($stoneCost, 4),
            'oxo_cost' => round($oxoCost, 4),
            'mino_cost' => round($minoCost, 4),
            'chips_cost' => round($chipsCost, 4),
            'gross_total' => round($grossTotal, 4),
            'wastage_amount' => round($wastageAmount, 4),
            'total_with_wastage' => round($totalWithWastage, 4),
            'price_after_markup1' => round($priceAfterMarkup1, 4),
            'final_price' => round($finalPrice, 4),
        ];
    }

    public function saveCosting(Product $product, array $calculatedData, ?int $userId = null): ProductCosting
    {
        return ProductCosting::create([
            'product_id' => $product->id,
            'weight' => $calculatedData['weight'],
            'mino_count' => $calculatedData['mino_count'],
            'stone_count' => $calculatedData['stone_count'],
            'chips_count' => $calculatedData['chips_count'],
            'chips_cost' => $calculatedData['chips_cost'],
            'oxo_factor' => $calculatedData['oxo_factor'],
            'metal_rate' => $calculatedData['metal_rate'],
            'stone_rate' => $calculatedData['stone_rate'],
            'mino_rate' => $calculatedData['mino_rate'],
            'wastage_percentage' => $calculatedData['wastage_percentage'],
            'markup_1' => $calculatedData['markup_1'],
            'markup_2' => $calculatedData['markup_2'],
            'metal_cost' => $calculatedData['metal_cost'],
            'stone_cost' => $calculatedData['stone_cost'],
            'oxo_cost' => $calculatedData['oxo_cost'],
            'mino_cost' => $calculatedData['mino_cost'],
            'gross_total' => $calculatedData['gross_total'],
            'wastage_amount' => $calculatedData['wastage_amount'],
            'total_with_wastage' => $calculatedData['total_with_wastage'],
            'price_after_markup1' => $calculatedData['price_after_markup1'],
            'final_price' => $calculatedData['final_price'],
            'costing_date' => now(),
            'created_by' => $userId,
        ]);
    }

    public function getCurrentRates(): array
    {
        return [
            'metal_rate' => RateConfiguration::getActiveRate('METAL_RATE', 1.1),
            'stone_rate_std' => RateConfiguration::getActiveRate('STONE_RATE_STD', 0.06),
            'stone_rate_high' => RateConfiguration::getActiveRate('STONE_RATE_HIGH', 0.1),
            'stone_rate_low' => RateConfiguration::getActiveRate('STONE_RATE_LOW', 0.07),
            'mino_rate' => RateConfiguration::getActiveRate('MINO_RATE', 0.035),
            'wastage_percentage' => RateConfiguration::getActiveRate('GHASARO_PCT', 0.03),
            'markup_1' => RateConfiguration::getActiveRate('MARKUP_1', 1.25),
            'markup_2' => RateConfiguration::getActiveRate('MARKUP_2', 1.65),
            'chips_rate' => RateConfiguration::getActiveRate('CHIPS_RATE', 0.15),
        ];
    }

    private function getStoneRateByType(string $type, array $rates): float
    {
        return match($type) {
            'S' => $rates['stone_rate_high'] ?? 0.1,
            'G', 'A' => $rates['stone_rate_low'] ?? 0.07,
            default => $rates['stone_rate_std'] ?? 0.06,
        };
    }
}
