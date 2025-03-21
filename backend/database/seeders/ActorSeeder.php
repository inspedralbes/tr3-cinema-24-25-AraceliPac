<?php

// database/seeders/ActorSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Actor;

class ActorSeeder extends Seeder
{
    /**
     * Executa el seeder.
     */
    public function run()
    {
        // Defineix els actors inicials
        $actors = [
            [
                'name' => 'Renée',
                'lastname' => 'Zellweger',
                'birth_date' => '1969-04-25',
                'nationality' => 'Americana',
                'bio' => 'Actriu coneguda per les seves interpretacions a "Bridget Jones" i "Chicago".',
                'image' => 'renee_zellweger.jpg',
            ],
            [
                'name' => 'Hugh',
                'lastname' => 'Grant',
                'birth_date' => '1960-09-09',
                'nationality' => 'Britànic',
                'bio' => 'Actor conegut per les seves interpretacions a "Notting Hill" i "Four Weddings and a Funeral".',
                'image' => 'hugh_grant.jpg',
            ],
            [
                'name' => 'Nicole',
                'lastname' => 'Kidman',
                'birth_date' => '1967-06-20',
                'nationality' => 'Australiana',
                'bio' => 'Actriu coneguda per les seves interpretacions a "Moulin Rouge!" i "The Hours".',
                'image' => 'nicole_kidman.jpg',
            ],
            [
                'name' => 'Anthony',
                'lastname' => 'Mackie',
                'birth_date' => '1978-09-23',
                'nationality' => 'Americà',
                'bio' => 'Actor conegut per interpretar Falcon a l\'Univers Cinematogràfic de Marvel.',
                'image' => 'anthony_mackie.jpg',
            ],
            [
                'name' => 'Michelle',
                'lastname' => 'Jenner',
                'birth_date' => '1986-09-14',
                'nationality' => 'Espanyola',
                'bio' => 'Actriu coneguda per la seva interpretació a "Isabel" i "Las chicas del cable".',
                'image' => 'michelle_jenner.jpg',
            ],
            [
                'name' => 'Mario',
                'lastname' => 'Casas',
                'birth_date' => '1986-06-12',
                'nationality' => 'Espanyol',
                'bio' => 'Actor conegut per les seves interpretacions a "Tres metros sobre el cielo" i "El bar".',
                'image' => 'mario_casas.jpg',
            ],
            [
                'name' => 'Theo',
                'lastname' => 'James',
                'birth_date' => '1984-12-16',
                'nationality' => 'Britànic',
                'bio' => 'Actor conegut per la seva interpretació a la saga "Divergent".',
                'image' => 'theo_james.jpg',
            ],
            [
                'name' => 'Elijah',
                'lastname' => 'Wood',
                'birth_date' => '1981-01-28',
                'nationality' => 'Americà',
                'bio' => 'Actor conegut per la seva interpretació de Frodo Baggins a "El Senyor dels Anells".',
                'image' => 'elijah_wood.jpg',
            ],
        ];

        // Insereix els actors a la base de dades
        foreach ($actors as $actor) {
            Actor::create($actor);
        }
    }
}
