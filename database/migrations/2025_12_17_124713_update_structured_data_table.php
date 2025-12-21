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
        if (!Schema::hasTable('soft_structured_data')) {
            return;
        }

        Schema::table('soft_structured_data', function (Blueprint $table) {
            if (!Schema::hasColumn('soft_structured_data', 'reference')) {
                $table->string('reference')->nullable();
            }
            if (!Schema::hasColumn('soft_structured_data', 'name')) {
                $table->string('name')->nullable();
            }
            if (!Schema::hasColumn('soft_structured_data', 'image')) {
                $table->string('image')->nullable();
            }
            if (!Schema::hasColumn('soft_structured_data', 'price')) {
                $table->decimal('price', 12, 2)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('soft_structured_data', function (Blueprint $table) {
            //
        });
    }
};
