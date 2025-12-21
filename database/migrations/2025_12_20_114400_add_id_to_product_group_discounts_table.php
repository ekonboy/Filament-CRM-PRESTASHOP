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
        Schema::table('product_group_discounts', function (Blueprint $table) {
            // Drop existing primary key first
            $table->dropPrimary();
            
            // Add id column as primary key
            $table->bigInteger('id')->unsigned()->first();
            $table->primary('id');
            
            // Re-add composite index for the original columns
            $table->index(['id_product', 'id_group']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_group_discounts', function (Blueprint $table) {
            //
        });
    }
};
