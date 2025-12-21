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
        Schema::create('soft_email_templates', function (Blueprint $table) {
            $table->id('id_email_template');
            $table->string('name');
            $table->boolean('active')->default(true);
        });

        Schema::create('soft_email_template_lang', function (Blueprint $table) {
            $table->integer('id_template');
            $table->integer('id_lang');
            $table->string('subject')->nullable();
            $table->longText('html_content')->nullable();
            
            $table->primary(['id_template', 'id_lang']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('soft_email_template_lang');
        Schema::dropIfExists('soft_email_templates');
    }
};
