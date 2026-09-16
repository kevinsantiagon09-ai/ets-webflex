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
        Schema::table('users', function (Blueprint $table) {
         $table->uuid('uuid')->unique();
         $table->string('lastname',100)->nullable();
         $table->string('phone', 30)->nullable();
         $table->string('is_active', 10)->nullable();
         $table->foreignId('role_id')
                ->nullable()
                ->after('is_active')
                ->constrained('roles')
                ->nullOnDelete();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
