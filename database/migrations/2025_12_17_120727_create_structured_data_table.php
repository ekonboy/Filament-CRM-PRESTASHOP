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
        if (Schema::hasTable('soft_structured_data')) {
            Schema::table('soft_structured_data', function (Blueprint $table) {
                if (!Schema::hasColumn('soft_structured_data', 'product_id')) {
                    $table->unsignedInteger('product_id');
                }
                if (!Schema::hasColumn('soft_structured_data', 'lang_id')) {
                    $table->unsignedTinyInteger('lang_id')->default(1);
                }
                if (!Schema::hasColumn('soft_structured_data', 'json_ld')) {
                    $table->longText('json_ld');
                }
                if (!Schema::hasColumn('soft_structured_data', 'created_at')) {
                    $table->timestamps();
                }
            });

            return;
        }

        Schema::create('soft_structured_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('product_id');
            $table->unsignedTinyInteger('lang_id')->default(1); // 1=Español, 2=Inglés...
            $table->longText('json_ld');
            $table->timestamps();
        });

        if (Schema::hasTable('soft_product')) {
            Schema::table('soft_structured_data', function (Blueprint $table) {
                $table->foreign('product_id')
                    ->references('id_product')
                    ->on('soft_product')
                    ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soft_structured_data');
    }
};
