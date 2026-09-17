<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('window_modals', function (Blueprint $table) {
            $table->id();

            $table->uuid('uuid')->unique();

            $table->text('description')->nullable();

            $table->string('image', 255)->nullable();

            // Usuario que creó/configuró la ventana modal.
            // Si se elimina el usuario, la ventana permanece.
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Estado de la ventana modal.
            $table->foreignId('estado_id')
                ->constrained('estados')
                ->restrictOnDelete();

            $table->timestamps();

            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('window_modals');
    }
};
