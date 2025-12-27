<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('soft_product_lang', function (Blueprint $table) {
            // First check if the id column already exists
            if (!Schema::hasColumn('soft_product_lang', 'id')) {
                // Add auto-increment primary key at the beginning
                $table->id('id')->first();
            }
            
            // Add unique constraint if it doesn't exist
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $indexes = $sm->listTableIndexes('soft_product_lang');
            
            if (!isset($indexes['product_lang_unique'])) {
                $table->unique(['id_product', 'id_shop', 'id_lang'], 'product_lang_unique');
            }
        });
    }

    public function down(): void
    {
        Schema::table('soft_product_lang', function (Blueprint $table) {
            $table->dropUnique('product_lang_unique');
            $table->dropColumn('id');
        });
    }
};
