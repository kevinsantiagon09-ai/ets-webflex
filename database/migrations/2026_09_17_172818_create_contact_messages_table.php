<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();

            $table->uuid('uuid')->unique();

            $table->string('name', 100);

            $table->string('last_name', 100)
                ->nullable();

            $table->string('email', 150);

            $table->string('phone', 30)
                ->nullable();

            $table->string('company', 150)
                ->nullable();

            $table->text('message');

            // Para saber si ya fue revisado
            $table->boolean('is_read')
                ->default(false);

            $table->timestamps();

            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
    }
};

