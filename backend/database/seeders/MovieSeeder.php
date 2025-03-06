<?php

// database/seeders/MovieSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Movie;
use App\Models\Genre;
use App\Models\Director;

class MovieSeeder extends Seeder
{
    /**
     * Executa el seeder.
     */
    public function run()
    {
        // Defineix les pel·lícules inicials
        $movies = [
            [
                'title' => 'Capitán América: Brave New World',
                'description' => 'La nova aventura del super-soldat en un món canviant.',
                'release_year' => 2024,
                'rating' => 'PG-13',
                'duration' => 150,
                'genre_id' => Genre::where('name', 'Acció')->first()->id,
                'director_id' => Director::where('lastname', 'Onah')->first()->id,
            ],
            [
                'title' => 'Bridget Jones: Loca per ell',
                'description' => 'Bridget Jones torna amb noves comicitats i embolics amorosos.',
                'release_year' => 2024,
                'rating' => 'R',
                'duration' => 120,
                'genre_id' => Genre::where('name', 'Comèdia')->first()->id,
                'director_id' => Director::where('lastname', 'Zellweger')->first()->id,
            ],
            [
                'title' => 'Paddington: Aventura en la selva',
                'description' => 'Paddington explora la selva en una nova aventura emocionant.',
                'release_year' => 2024,
                'rating' => 'PG',
                'duration' => 110,
                'genre_id' => Genre::where('name', 'Acció')->first()->id,
                'director_id' => Director::where('lastname', 'King')->first()->id,
            ],
            [
                'title' => 'El secret del orfebre',
                'description' => 'Un misteriós orfebre amaga un secret que canviarà tot.',
                'release_year' => 2024,
                'rating' => 'PG-13',
                'duration' => 130,
                'genre_id' => Genre::where('name', 'Drama')->first()->id,
                'director_id' => Director::where('lastname', 'del Toro')->first()->id,
            ],
            [
                'title' => 'The Monkey',
                'description' => 'Un thriller psicològic basat en una història de Stephen King.',
                'release_year' => 2024,
                'rating' => 'R',
                'duration' => 140,
                'genre_id' => Genre::where('name', 'Terror')->first()->id,
                'director_id' => Director::where('lastname', 'Wan')->first()->id,
            ],
        ];

        // Insereix les pel·lícules a la base de dades
        foreach ($movies as $movie) {
            Movie::create($movie);
        }
    }
}
