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
        Schema::create('product_group_discounts', function (Blueprint $table) {
            $table->unsignedInteger('id_product');
            $table->unsignedInteger('id_group');
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->timestamps();
            
            $table->primary(['id_product', 'id_group']);
            $table->index('id_product');
            $table->index('id_group');
            $table->index('discount_percentage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_group_discounts');
    }
};
