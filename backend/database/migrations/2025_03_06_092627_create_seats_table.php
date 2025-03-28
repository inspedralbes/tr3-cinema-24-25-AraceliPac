<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('screening_id')->constrained()->onDelete('cascade'); // Relació amb screenings
            $table->char('row', 1); // Fila (A-L)
            $table->integer('number'); // Número de la butaca (1-10)
            $table->boolean('is_vip')->default(false); 
            $table->boolean('is_occupied')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};
