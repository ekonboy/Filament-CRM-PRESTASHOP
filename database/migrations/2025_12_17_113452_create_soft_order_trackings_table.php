<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('soft_order_trackings')) {
            Schema::table('soft_order_trackings', function (Blueprint $table) {
                if (!Schema::hasColumn('soft_order_trackings', 'id_order')) {
                    $table->unsignedBigInteger('id_order');
                }
                if (!Schema::hasColumn('soft_order_trackings', 'reference')) {
                    $table->string('reference')->nullable();
                }
                if (!Schema::hasColumn('soft_order_trackings', 'customer_name')) {
                    $table->string('customer_name')->nullable();
                }
                if (!Schema::hasColumn('soft_order_trackings', 'country')) {
                    $table->string('country')->nullable();
                }
                if (!Schema::hasColumn('soft_order_trackings', 'tracking_code')) {
                    $table->string('tracking_code')->nullable();
                }
                if (!Schema::hasColumn('soft_order_trackings', 'status')) {
                    $table->string('status')->default('pendiente');
                }
                if (!Schema::hasColumn('soft_order_trackings', 'sent_at')) {
                    $table->timestamp('sent_at')->nullable();
                }
                if (!Schema::hasColumn('soft_order_trackings', 'created_at')) {
                    $table->timestamps();
                }
            });

            return;
        }

        Schema::create('soft_order_trackings', function (Blueprint $table) {
            $table->id(); // id auto incremental
            $table->unsignedBigInteger('id_order')->unique();
            $table->string('reference')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('country')->nullable();
            $table->string('tracking_code')->nullable();
            $table->string('status')->default('pendiente'); // pendiente / enviado
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('soft_order_trackings');
    }
};

