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
                'name' => 'Julius',
                'lastname' => 'Onah',
                'birth_date' => '1983-01-01', // Data de naixement fictícia
                'nationality' => 'Nigerià',
                'bio' => 'Director conegut per "Capitán América: Brave New World".',
            ],
            [
                'name' => 'Renée',
                'lastname' => 'Zellweger',
                'birth_date' => '1969-04-25',
                'nationality' => 'Americana',
                'bio' => 'Actriu i directora coneguda per "Bridget Jones: Loca per ell".',
            ],
            [
                'name' => 'Paul',
                'lastname' => 'King',
                'birth_date' => '1978-07-29',
                'nationality' => 'Britànic',
                'bio' => 'Director conegut per les pel·lícules de "Paddington".',
            ],
            [
                'name' => 'Guillermo',
                'lastname' => 'del Toro',
                'birth_date' => '1964-10-09',
                'nationality' => 'Mexicà',
                'bio' => 'Director conegut per "The Shape of Water" i "Pan\'s Labyrinth".',
            ],
            [
                'name' => 'James',
                'lastname' => 'Wan',
                'birth_date' => '1977-02-26',
                'nationality' => 'Australià',
                'bio' => 'Director conegut per "The Conjuring" i "Aquaman".',
            ],
            [
                'name' => 'Christopher',
                'lastname' => 'Nolan',
                'birth_date' => '1970-07-30',
                'nationality' => 'Britànic',
                'bio' => 'Director conegut per "Inception" i "The Dark Knight".',
            ],
            [
                'name' => 'Quentin',
                'lastname' => 'Tarantino',
                'birth_date' => '1963-03-27',
                'nationality' => 'Americà',
                'bio' => 'Director conegut per "Pulp Fiction" i "Kill Bill".',
            ],
            [
                'name' => 'Greta',
                'lastname' => 'Gerwig',
                'birth_date' => '1983-08-04',
                'nationality' => 'Americana',
                'bio' => 'Directora coneguda per "Lady Bird" i "Little Women".',
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
        ];

        // Insereix els directors a la base de dades
        foreach ($directors as $director) {
            Director::create($director);
        }
    }
}
