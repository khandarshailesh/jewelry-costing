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
        Schema::create('labor_rates', function (Blueprint $table) {
            $table->id();
            $table->string('process_name', 100);  // Chips, MINO, FUSIYA, etc.
            $table->string('sub_type', 100)->nullable();  // white, mor pankh, etc.
            $table->string('size', 50)->nullable();  // nani, moti, etc.
            $table->decimal('material_cost', 10, 4)->default(0);
            $table->decimal('labor_cost', 10, 4)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['process_name', 'sub_type', 'size'], 'unique_labor_rate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('labor_rates');
    }
};
