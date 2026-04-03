<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\OxoFactor;

class OxoFactorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $oxoFactors = [
            [
                'value' => 0,
                'name' => 'No OXO',
                'description' => 'OXO factor not applied',
                'is_active' => true,
            ],
            [
                'value' => 1,
                'name' => 'OXO Applied',
                'description' => 'OXO factor applied',
                'is_active' => true,
            ],
        ];

        foreach ($oxoFactors as $oxoFactor) {
            OxoFactor::updateOrCreate(
                ['value' => $oxoFactor['value']],
                $oxoFactor
            );
        }
    }
}
