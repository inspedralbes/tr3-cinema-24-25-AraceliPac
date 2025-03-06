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
        Schema::create('role_user', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade'); // Relación con la tabla users
            $table->foreignId('role_id')->references('id')->on('roles')->onDelete('cascade'); // Relación con la tabla roles
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_user');
    }
};
