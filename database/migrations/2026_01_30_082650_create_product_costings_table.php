<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_costings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();

            // Input values snapshot
            $table->decimal('weight', 10, 4);
            $table->integer('mino_count')->default(0);
            $table->integer('stone_count')->default(0);
            $table->integer('chips_count')->default(0);
            $table->decimal('chips_cost', 10, 4)->default(0);
            $table->decimal('oxo_factor', 5, 2)->default(0);

            // Rate snapshot at time of costing
            $table->decimal('metal_rate', 10, 4);
            $table->decimal('stone_rate', 10, 4);
            $table->decimal('mino_rate', 10, 4);
            $table->decimal('wastage_percentage', 5, 4);
            $table->decimal('markup_1', 5, 2);
            $table->decimal('markup_2', 5, 2);

            // Calculated costs
            $table->decimal('metal_cost', 10, 4);
            $table->decimal('stone_cost', 10, 4);
            $table->decimal('oxo_cost', 10, 4);
            $table->decimal('mino_cost', 10, 4);
            $table->decimal('gross_total', 10, 4);
            $table->decimal('wastage_amount', 10, 4);
            $table->decimal('total_with_wastage', 10, 4);
            $table->decimal('price_after_markup1', 10, 4);
            $table->decimal('final_price', 10, 4);

            // Metadata
            $table->date('costing_date')->default(now());
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('costing_date');
            $table->index('final_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_costings');
    }
};
