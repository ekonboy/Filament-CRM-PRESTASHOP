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
        if (Schema::hasTable('login_logs')) {
            Schema::table('login_logs', function (Blueprint $table) {
                if (!Schema::hasColumn('login_logs', 'user_id')) {
                    $table->unsignedBigInteger('user_id')->index();
                }
                if (!Schema::hasColumn('login_logs', 'user_name')) {
                    $table->string('user_name')->nullable();
                }
                if (!Schema::hasColumn('login_logs', 'email')) {
                    $table->string('email')->nullable();
                }
                if (!Schema::hasColumn('login_logs', 'ip_address')) {
                    $table->string('ip_address', 45)->nullable();
                }
                if (!Schema::hasColumn('login_logs', 'user_agent')) {
                    $table->string('user_agent')->nullable();
                }
                if (!Schema::hasColumn('login_logs', 'browser')) {
                    $table->string('browser')->nullable();
                }
                if (!Schema::hasColumn('login_logs', 'platform')) {
                    $table->string('platform')->nullable();
                }
                if (!Schema::hasColumn('login_logs', 'created_at')) {
                    $table->timestamps();
                }
            });

            return;
        }

        Schema::create('login_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('user_name');
            $table->string('email');
            $table->string('ip_address', 45);
            $table->string('user_agent')->nullable();
            $table->string('browser')->nullable();
            $table->string('platform')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('login_logs');
    }
};
