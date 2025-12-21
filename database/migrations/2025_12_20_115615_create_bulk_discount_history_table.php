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
        Schema::create('bulk_discount_history', function (Blueprint $table) {
            $table->id();
            $table->string('batch_id')->unique();
            $table->json('group_ids');
            $table->decimal('discount_percent', 5, 2);
            $table->unsignedInteger('products_affected')->default(0);
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'rolled_back'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('batch_id');
            $table->index('status');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bulk_discount_history');
    }
};
