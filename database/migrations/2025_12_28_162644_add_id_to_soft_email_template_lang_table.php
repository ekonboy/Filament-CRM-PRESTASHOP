<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('soft_email_template_lang', function (Blueprint $table) {
            if (!Schema::hasColumn('soft_email_template_lang', 'id')) {
                $table->unsignedBigInteger('id')->first();
            }
        });
    }


    // public function up(): void
    // {
    //     Schema::table('soft_email_template_lang', function (Blueprint $table) {
    //         $table->id()->first();
    //     });
    // }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('soft_email_template_lang', function (Blueprint $table) {
            $table->dropColumn('id');
        });
    }
};
