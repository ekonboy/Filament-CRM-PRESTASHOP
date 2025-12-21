<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('price_change_logs', function (Blueprint $table) {
            $table->id();
            $table->string('batch_id', 36)->index();
            $table->unsignedInteger('id_product');
            $table->decimal('old_price', 20, 6);
            $table->decimal('new_price', 20, 6);
            $table->decimal('discount_percent', 5, 2);
            $table->json('group_ids');
            $table->string('status')->default('applied');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();

            $table->index(['batch_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('price_change_logs');
    }
};
