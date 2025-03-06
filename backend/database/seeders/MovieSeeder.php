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
                'image' => 'https://media.vandalsports.com/i/1706x960/3-2025/2025331568_1.jpg.webp',
                'trailer' => 'https://youtu.be/wl2I9HOovUQ?si=mqtzmBk8iAmZ6UPM', // URL del tràiler
                'genre_id' => Genre::where('name', 'Acció')->first()->id,
                'director_id' => Director::where('lastname', 'Onah')->first()->id,
            ],
            [
                'title' => 'Bridget Jones: Loca per ell',
                'description' => 'Bridget Jones torna amb noves comicitats i embolics amorosos.',
                'release_year' => 2024,
                'rating' => 'R',
                'duration' => 120,
                'image' => 'https://peru21-pe.b-cdn.net/sites/default/efsfiles/2024-12/bridget-jones.png',
                'trailer' => 'https://youtu.be/o6F5Mnm9sNM?si=ARkuNwAee2__-XXO', // URL del tràiler
                'genre_id' => Genre::where('name', 'Comèdia')->first()->id,
                'director_id' => Director::where('lastname', 'Zellweger')->first()->id,
            ],
            [
                'title' => 'Paddington: Aventura en la selva',
                'description' => 'Paddington explora la selva en una nova aventura emocionant.',
                'release_year' => 2024,
                'rating' => 'PG',
                'duration' => 110,
                'image' => 'https://offloadmedia.feverup.com/secretldn.com/wp-content/uploads/2024/09/08152932/PinPep_PaddingtonWestfield_DP_8195-1024x696.jpg',
                'trailer' => 'https://youtu.be/l1qdCIyNErg?si=SxgycX02xeS9EuPF', // URL del tràiler
                'genre_id' => Genre::where('name', 'Acció')->first()->id,
                'director_id' => Director::where('lastname', 'King')->first()->id,
            ],
            [
                'title' => 'El secret del orfebre',
                'description' => 'Un misteriós orfebre amaga un secret que canviarà tot.',
                'release_year' => 2024,
                'rating' => 'PG-13',
                'duration' => 130,
                'image' => 'https://www.libreriamujeres.com/513075-large_default/el-secreto-del-orfebre.jpg',
                'trailer' => 'https://youtu.be/fBHsIT-KHAs?si=AGPzA4AVUEV8kSnP', // URL del tràiler
                'genre_id' => Genre::where('name', 'Drama')->first()->id,
                'director_id' => Director::where('lastname', 'del Toro')->first()->id,
            ],
            [
                'title' => 'The Monkey',
                'description' => 'Un thriller psicològic basat en una història de Stephen King.',
                'release_year' => 2024,
                'rating' => 'R',
                'duration' => 140,
                'image' => 'https://rubik-audiovisual.com/wp-content/uploads/2025/02/the-monkey-2-1024x512.jpg',
                'trailer' => 'https://youtu.be/hOzVJSGSGXA?si=0ygSuBE1oW2h-QTi', // URL del tràiler
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
