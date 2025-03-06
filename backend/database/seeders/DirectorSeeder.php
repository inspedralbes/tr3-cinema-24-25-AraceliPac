<?php

// database/seeders/DirectorSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Director;

class DirectorSeeder extends Seeder
{
    /**
     * Executa el seeder.
     */
    public function run()
    {
        // Defineix els directors inicials
        $directors = [
            [
                'name' => 'Christopher',
                'lastname' => 'Nolan',
                'birth_date' => '1970-07-30',
                'nationality' => 'Britànic',
                'bio' => 'Director conegut per pel·lícules com "Inception" i "The Dark Knight".',
            ],
            [
                'name' => 'Quentin',
                'lastname' => 'Tarantino',
                'birth_date' => '1963-03-27',
                'nationality' => 'Americà',
                'bio' => 'Director conegut per pel·lícules com "Pulp Fiction" i "Kill Bill".',
            ],
            [
                'name' => 'Greta',
                'lastname' => 'Gerwig',
                'birth_date' => '1983-08-04',
                'nationality' => 'Americana',
                'bio' => 'Directora coneguda per pel·lícules com "Lady Bird" i "Little Women".',
            ],
            [
                'name' => 'Steven',
                'lastname' => 'Spielberg',
                'birth_date' => '1946-12-18',
                'nationality' => 'Americà',
                'bio' => 'Director conegut per "Jurassic Park" i "Schindler\'s List".',
            ],
            [
                'name' => 'Martin',
                'lastname' => 'Scorsese',
                'birth_date' => '1942-11-17',
                'nationality' => 'Americà',
                'bio' => 'Director conegut per "Goodfellas" i "Taxi Driver".',
            ],
            [
                'name' => 'Sofia',
                'lastname' => 'Coppola',
                'birth_date' => '1971-05-14',
                'nationality' => 'Americana',
                'bio' => 'Directora coneguda per "Lost in Translation".',
            ],
            [
                'name' => 'Bong',
                'lastname' => 'Joon-ho',
                'birth_date' => '1969-09-14',
                'nationality' => 'Coreà',
                'bio' => 'Director conegut per "Parasite" i "Snowpiercer".',
            ],
            [
                'name' => 'Guillermo',
                'lastname' => 'del Toro',
                'birth_date' => '1964-10-09',
                'nationality' => 'Mexicà',
                'bio' => 'Director conegut per "The Shape of Water" i "Pan\'s Labyrinth".',
            ],
            [
                'name' => 'Hayao',
                'lastname' => 'Miyazaki',
                'birth_date' => '1941-01-05',
                'nationality' => 'Japonès',
                'bio' => 'Director conegut per "Spirited Away" i "My Neighbor Totoro".',
            ],
            [
                'name' => 'Pedro',
                'lastname' => 'Almodóvar',
                'birth_date' => '1949-09-25',
                'nationality' => 'Espanyol',
                'bio' => 'Director conegut per "Talk to Her" i "Volver".',
            ],
        ];

        // Insereix els directors a la base de dades
        foreach ($directors as $director) {
            Director::create($director);
        }
    }
}
