<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabla nativa de PrestaShop (Solo si no existe, para desarrollo local)
        if (!Schema::hasTable('soft_specific_price')) {
            Schema::create('soft_specific_price', function (Blueprint $table) {
                $table->id('id_specific_price');
                $table->integer('id_specific_price_rule')->default(0);
                $table->integer('id_cart')->default(0);
                $table->integer('id_product')->default(0)->index(); // 0 = Todos los productos
                $table->integer('id_shop')->default(1);
                $table->integer('id_shop_group')->default(0);
                $table->integer('id_currency')->default(0);
                $table->integer('id_country')->default(0);
                $table->integer('id_group')->default(0)->index();
                $table->integer('id_customer')->default(0);
                $table->integer('id_product_attribute')->default(0);
                $table->decimal('price', 20, 6)->default(-1.000000);
                $table->integer('from_quantity')->default(1);
                $table->decimal('reduction', 20, 6);
                $table->enum('reduction_tax', [0, 1])->default(1);
                $table->enum('reduction_type', ['amount', 'percentage']);
                $table->dateTime('from');
                $table->dateTime('to');

            });
        }



        // 2. Tu tabla personalizada de mensajes
        Schema::create('soft_custom_promo_text', function (Blueprint $table) {
            // El ID debe ser el mismo que el de specific_price (Relación 1 a 1)
            $table->unsignedBigInteger('id_specific_price')->primary();
            $table->string('promo_text', 255);

            // Definimos la clave primaria manual para que coincida con PrestaShop
            $table->primary('id_specific_price');

            // Clave foránea para asegurar que si borras el descuento, se borra el texto
            $table->foreign('id_specific_price')
                ->references('id_specific_price')
                ->on('soft_specific_price')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('soft_custom_promo_text');
        // No borramos soft_specific_price por seguridad ya que es de PrestaShop
    }
};
