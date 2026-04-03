<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\RateConfiguration;
use App\Models\LaborRate;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Seed stone types and oxo factors
        $this->call([
            StoneTypeSeeder::class,
            OxoFactorSeeder::class,
        ]);

        // Categories
        Category::insert([
            ['name' => 'Casting', 'code' => 'CASTING', 'created_at' => now(), 'updated_at' => now()],
            ['name' => '925 Silver', 'code' => '925', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Chain', 'code' => 'CHAIN', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lucky', 'code' => 'LUCKY', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Rate Configurations (from Excel)
        $rates = [
            ['name' => 'Metal Rate Multiplier', 'code' => 'METAL_RATE', 'rate_value' => 1.1, 'rate_type' => 'multiplier', 'description' => 'Base metal cost multiplier'],
            ['name' => 'Stone Rate Standard', 'code' => 'STONE_RATE_STD', 'rate_value' => 0.06, 'rate_type' => 'fixed', 'description' => 'Standard stone rate (K type)'],
            ['name' => 'Stone Rate High', 'code' => 'STONE_RATE_HIGH', 'rate_value' => 0.1, 'rate_type' => 'fixed', 'description' => 'High quality stone rate (S type)'],
            ['name' => 'Stone Rate Low', 'code' => 'STONE_RATE_LOW', 'rate_value' => 0.07, 'rate_type' => 'fixed', 'description' => 'Standard stone rate (G/A type)'],
            ['name' => 'MINO Rate', 'code' => 'MINO_RATE', 'rate_value' => 0.035, 'rate_type' => 'fixed', 'description' => 'MINO stone rate per piece'],
            ['name' => 'Wastage Percentage (Ghasaro)', 'code' => 'GHASARO_PCT', 'rate_value' => 0.03, 'rate_type' => 'percentage', 'description' => 'Wastage/Ghasaro percentage (3%)'],
            ['name' => 'Markup Level 1', 'code' => 'MARKUP_1', 'rate_value' => 1.25, 'rate_type' => 'multiplier', 'description' => 'First level markup (25%)'],
            ['name' => 'Markup Level 2', 'code' => 'MARKUP_2', 'rate_value' => 1.65, 'rate_type' => 'multiplier', 'description' => 'Second level markup (65%)'],
            ['name' => 'Chips Rate', 'code' => 'CHIPS_RATE', 'rate_value' => 0.15, 'rate_type' => 'fixed', 'description' => 'Default chips rate'],
            ['name' => 'Matt Rate', 'code' => 'MATT_RATE', 'rate_value' => 1.4, 'rate_type' => 'multiplier', 'description' => 'Matt finish multiplier'],
            ['name' => 'ASG Rate', 'code' => 'ASG_RATE', 'rate_value' => 1.1, 'rate_type' => 'multiplier', 'description' => 'A/S/G type multiplier'],
        ];

        foreach ($rates as $rate) {
            RateConfiguration::create(array_merge($rate, [
                'is_active' => true,
                'effective_from' => now(),
            ]));
        }

        // Labor Rates (from Majuri na bhav sheet)
        $laborRates = [
            ['process_name' => 'CASTING', 'sub_type' => '410 METAL', 'size' => '125 SUNIL', 'material_cost' => 525, 'labor_cost' => 1100],
            ['process_name' => 'Chips', 'sub_type' => 'white', 'size' => 'nani', 'material_cost' => 0.1, 'labor_cost' => 0.05],
            ['process_name' => 'Chips', 'sub_type' => 'white', 'size' => 'moti', 'material_cost' => 0.15, 'labor_cost' => 0.05],
            ['process_name' => 'Chips', 'sub_type' => 'mor pankh', 'size' => 'nani', 'material_cost' => 0.15, 'labor_cost' => 0.05],
            ['process_name' => 'Chips', 'sub_type' => 'mor pankh', 'size' => 'moti', 'material_cost' => 0.25, 'labor_cost' => 0.05],
            ['process_name' => 'fussiya', 'sub_type' => 'diamons', 'size' => 'M+M', 'material_cost' => 0.03, 'labor_cost' => 0.03],
            ['process_name' => 'MINO', 'sub_type' => 'POINT', 'size' => 'NANO', 'material_cost' => 0, 'labor_cost' => 0.03],
            ['process_name' => 'MINO', 'sub_type' => 'POINT', 'size' => 'MOTO', 'material_cost' => 0, 'labor_cost' => 0.05],
            ['process_name' => 'MINO', 'sub_type' => 'MOTO', 'size' => 'OM / SHREE', 'material_cost' => 0, 'labor_cost' => 0.12],
            ['process_name' => 'FUSIYA', 'sub_type' => 'DOOB', 'size' => null, 'material_cost' => 0.03, 'labor_cost' => 0.07],
        ];

        foreach ($laborRates as $rate) {
            LaborRate::create($rate);
        }
    }
}
