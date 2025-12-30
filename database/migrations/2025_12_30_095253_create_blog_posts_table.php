<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_author_id')->constrained('blog_authors')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique(); // Para la URL exitus.es/media/post-name
            $table->text('excerpt'); // Texto pequeño
            $table->longText('content'); // Contenido completo
            $table->string('image')->nullable(); // Foto del post
            $table->boolean('is_visible')->default(true);
            $table->date('published_at'); // Fecha de creación manual o automática
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};
