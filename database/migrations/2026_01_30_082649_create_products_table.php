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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_number', 20)->unique();
            $table->string('name', 255)->nullable();
            $table->decimal('weight', 10, 4);  // RASS - metal weight in grams
            $table->integer('mino_count')->default(0);
            $table->integer('stone_count')->default(0);
            $table->integer('chips_count')->default(0);
            $table->decimal('oxo_factor', 5, 2)->default(0);  // 0 or 1
            $table->enum('stone_type', ['k', 'S', 'G', 'A'])->default('k');
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('product_number');
            $table->index('stone_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
