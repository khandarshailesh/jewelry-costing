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
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('material_number', 20)->unique();  // M1, M2, M58, etc.
            $table->string('name', 255)->nullable();
            $table->decimal('weight', 10, 4)->nullable();  // RASS
            $table->decimal('price', 10, 4);  // BHAV
            $table->string('unit', 20)->default('piece');
            $table->string('category', 50)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('material_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
