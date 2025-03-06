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
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->year('release_year')->nullable();
            $table->string('rating', 10)->nullable();
            $table->integer('duration')->nullable();
            $table->string(('image'))->nullable();
            $table->string('trailer')->nullable();
            $table->unsignedBigInteger('genre_id');
            $table->unsignedBigInteger('director_id');
            $table->timestamps();

            $table->foreign('director_id')->references('id')->on('directors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
