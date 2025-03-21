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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->string('qr_code')->nullable(); 
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relació amb usuari
            $table->foreignId('screening_id')->constrained()->onDelete('cascade'); // Relació amb screenings
            $table->foreignId('seat_id')->constrained()->onDelete('cascade'); // Relació amb butaca
            $table->decimal('price', 5, 2); // Preu de l'entrada
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
