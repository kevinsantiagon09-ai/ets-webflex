<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();

            $table->uuid('uuid')->unique();

            // Usuario que realizó/configuró los cambios.
            // Si el usuario se elimina, la configuración permanece.
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Configuración visual
            $table->string('primary_color', 20)->nullable();
            $table->string('text_color', 20)->nullable();
            $table->string('font_family', 100)->nullable();
            $table->string('logo_path', 255)->nullable();
            $table->string('button_color', 20)->nullable();

            $table->timestamps();

            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};