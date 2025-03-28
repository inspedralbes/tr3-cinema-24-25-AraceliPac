<?php

// database/seeders/GenreSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Genre;

class GenreSeeder extends Seeder
{
    /**
     * Executa el seeder.
     */
    public function run()
    {
        // Defineix els gèneres inicials
        $genres = [
            ['name' => 'Acció', 'description' => 'Pelicul·les d\'alta intensitat i emocionants.'],
            ['name' => 'Comèdia', 'description' => 'Pelicul·les divertides i humorístiques.'],
            ['name' => 'Romàntica', 'description' => 'Pelicul·les d\'amor i relacions.'],
            ['name' => 'Drama', 'description' => 'Pelicul·les emocionals i profundes.'],
            ['name' => 'Musicals', 'description' => 'Pelicul·les amb fragments musicals.'],
            ['name' => 'Documentals', 'description' => 'Pelicul·les representacions de la realitat.'],
            ['name' => 'Terror', 'description' => 'Pelicul·les que provoquen por i tensió.'],
            ['name' => 'Ciència-ficció', 'description' => 'Pelicul·les amb temàtiques futuristes i tecnològiques.'],
        ];

        // Insereix els gèneres a la base de dades
        foreach ($genres as $genre) {
            Genre::create($genre);
        }
    }
}
