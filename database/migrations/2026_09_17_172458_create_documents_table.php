<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();

            $table->uuid('uuid')->unique();

            // Usuario que subió el documento.
            // Si se elimina el usuario, el documento permanece.
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Estado del documento.
            $table->foreignId('estado_id')
                ->constrained('estados')
                ->restrictOnDelete();

            $table->string('name', 150);

            $table->text('description')
                ->nullable();

            // Ruta física/lógica donde se almacena el archivo.
            $table->string('file_path', 255);

            // Nombre real del archivo guardado.
            $table->string('file_name', 255);

            // Ejemplo: application/pdf
            $table->string('mime_type', 100)
                ->nullable();

            // Tamaño del archivo en bytes.
            $table->unsignedBigInteger('file_size')
                ->nullable();

            // Cantidad de veces que se ha descargado.
            $table->unsignedInteger('download_count')
                ->default(0);

            $table->timestamps();

            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};