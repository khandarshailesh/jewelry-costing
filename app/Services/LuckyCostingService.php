<?php

namespace App\Services;

class LuckyCostingService
{
    /**
     * LUCKY COSTING FORMULA (from Excel Sheet4 - RAMDEV LUCKY section):
     *
     * Total = Weight + Mino + Diamond + Packing
     * After Markup 1 = Total × 1.25
     * Final Price = After Markup 1 × 1.65
     */

    public function calculate(
        float $weight,
        float $minoCount = 0,
        float $diamondCost = 0,
        float $packingCost = 1,
        float $markup1 = 1.25,
        float $markup2 = 1.65
    ): array {
        $totalCost = $weight + $minoCount + $diamondCost + $packingCost;
        $priceAfterMarkup1 = $totalCost * $markup1;
        $finalPrice = $priceAfterMarkup1 * $markup2;

        return [
            'weight' => $weight,
            'mino_count' => $minoCount,
            'diamond_cost' => $diamondCost,
            'packing_cost' => $packingCost,
            'markup_1' => $markup1,
            'markup_2' => $markup2,
            'total_cost' => round($totalCost, 4),
            'price_after_markup1' => round($priceAfterMarkup1, 4),
            'final_price' => round($finalPrice, 4),
        ];
    }
}
