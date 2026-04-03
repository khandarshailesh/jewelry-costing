<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StoneType;

class StoneTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stoneTypes = [
            [
                'code' => 'k',
                'name' => 'K - Standard',
                'price_per_unit' => 0.06,
                'description' => 'Standard stone type',
                'is_active' => true,
            ],
            [
                'code' => 'S',
                'name' => 'S - Special',
                'price_per_unit' => 0.10,
                'description' => 'Special stone type',
                'is_active' => true,
            ],
            [
                'code' => 'G',
                'name' => 'G - Other',
                'price_per_unit' => 0.07,
                'description' => 'Other stone type G',
                'is_active' => true,
            ],
            [
                'code' => 'A',
                'name' => 'A - Other',
                'price_per_unit' => 0.07,
                'description' => 'Other stone type A',
                'is_active' => true,
            ],
        ];

        foreach ($stoneTypes as $stoneType) {
            StoneType::updateOrCreate(
                ['code' => $stoneType['code']],
                $stoneType
            );
        }
    }
}
