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
        Schema::create('chain_items', function (Blueprint $table) {
            $table->id();
            $table->string('item_number', 20)->unique();  // C-01, C-02, etc.
            $table->decimal('weight', 10, 4);  // VAJAN
            $table->decimal('base_rate', 10, 2)->default(225);
            $table->decimal('making_charge', 10, 2)->default(25);
            $table->decimal('pcs_rate', 10, 2)->nullable();
            $table->decimal('markup_1', 5, 2)->default(1.25);
            $table->decimal('markup_2', 5, 2)->default(1.5);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chain_items');
    }
};
