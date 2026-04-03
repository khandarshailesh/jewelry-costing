<?php

namespace App\Services;

class ChainCostingService
{
    /**
     * CHAIN COSTING FORMULA (from Excel Sheet4 - 925 section):
     *
     * J = Weight × Base Rate (225)
     * K = J + Making Charge (25)
     * L = K × Markup 1 (1.25)
     * M = L × Markup 2 (1.5) = Final Price
     */

    public function calculate(
        float $weight,
        float $baseRate = 225,
        float $makingCharge = 25,
        float $markup1 = 1.25,
        float $markup2 = 1.5
    ): array {
        $weightCost = $weight * $baseRate;
        $costWithMaking = $weightCost + $makingCharge;
        $priceAfterMarkup1 = $costWithMaking * $markup1;
        $finalPrice = $priceAfterMarkup1 * $markup2;

        return [
            'weight' => $weight,
            'base_rate' => $baseRate,
            'making_charge' => $makingCharge,
            'markup_1' => $markup1,
            'markup_2' => $markup2,
            'weight_cost' => round($weightCost, 4),
            'cost_with_making' => round($costWithMaking, 4),
            'price_after_markup1' => round($priceAfterMarkup1, 4),
            'final_price' => round($finalPrice, 4),
        ];
    }
}
