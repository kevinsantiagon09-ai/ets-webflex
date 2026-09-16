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
       Schema::create('estados', function (Blueprint $table) {

    $table->id();
    $table->uuid('uuid')->unique();
    $table->unsignedBigInteger('user_id');
    $table->integer('activo')->default(1);
    $table->integer('inactivo')->default(0);
    $table->integer('softdelete')->default(0);
    $table->foreign('user_id')
        ->references('id')
        ->on('users')
        ->cascadeOnUpdate()
        ->cascadeOnDelete();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estados');
    }
};
