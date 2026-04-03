<?php

namespace App\Services;

use App\Models\Material;

class BOMService
{
    /**
     * BOM CALCULATION (from Excel Sheet7):
     *
     * Uses VLOOKUP to find material price and weight from MATERIAL_COST_LIST_WITH_WEIGHT
     * Total Price = Unit Price × Quantity
     * Total Weight = Unit Weight × Quantity
     */

    public function calculate(string $materialNumber, float $quantity): array
    {
        $material = Material::lookup($materialNumber);

        if (!$material) {
            return [
                'material_number' => $materialNumber,
                'quantity' => $quantity,
                'error' => 'Material not found',
                'unit_price' => null,
                'total_price' => null,
                'unit_weight' => null,
                'total_weight' => null,
            ];
        }

        $unitPrice = $material->price;
        $unitWeight = $material->weight ?? 0;

        return [
            'material_number' => $materialNumber,
            'material_name' => $material->name,
            'quantity' => $quantity,
            'unit_price' => round($unitPrice, 4),
            'total_price' => round($unitPrice * $quantity, 4),
            'unit_weight' => round($unitWeight, 4),
            'total_weight' => round($unitWeight * $quantity, 4),
        ];
    }

    public function calculateMultiple(array $items): array
    {
        $results = [];
        $grandTotalPrice = 0;
        $grandTotalWeight = 0;

        foreach ($items as $item) {
            $result = $this->calculate($item['material_number'], $item['quantity']);
            $results[] = $result;

            if (!isset($result['error'])) {
                $grandTotalPrice += $result['total_price'];
                $grandTotalWeight += $result['total_weight'];
            }
        }

        return [
            'items' => $results,
            'grand_total_price' => round($grandTotalPrice, 4),
            'grand_total_weight' => round($grandTotalWeight, 4),
        ];
    }
}
