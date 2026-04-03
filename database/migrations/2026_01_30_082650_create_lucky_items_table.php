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
        Schema::create('lucky_items', function (Blueprint $table) {
            $table->id();
            $table->string('item_number', 20)->unique();  // L-41, L-42, etc.
            $table->decimal('weight', 10, 4);  // vajan
            $table->decimal('mino_count', 5, 2)->default(0);
            $table->decimal('diamond_cost', 10, 4)->default(0);
            $table->decimal('packing_cost', 10, 4)->default(1);
            $table->decimal('total_cost', 10, 4);
            $table->decimal('markup_1', 5, 2)->default(1.25);
            $table->decimal('markup_2', 5, 2)->default(1.65);
            $table->decimal('price_after_markup1', 10, 4)->nullable();
            $table->decimal('final_price', 10, 4)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lucky_items');
    }
};
