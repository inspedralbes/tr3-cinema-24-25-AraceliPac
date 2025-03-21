<?php

// database/seeders/MovieActorSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Movie;
use App\Models\Actor;
use Illuminate\Support\Facades\DB;

class MovieActorSeeder extends Seeder
{
    /**
     * Executa el seeder.
     */
    public function run()
    {
        // Defineix les relacions entre pel·lícules i actors
        $relations  = [
            [
                'movie_id' => 1, // ID de la pel·lícula
                'actor_id' => 4, // ID de l'actor
            ],
            [
                'movie_id' => 2,
                'actor_id' => 1,
            ],
            [
                'movie_id' => 2,
                'actor_id' => 2,
            ],
            [
                'movie_id' => 3,
                'actor_id' => 3,
            ],
            [
                'movie_id' => 4,
                'actor_id' => 5,
            ],
            [
                'movie_id' => 4,
                'actor_id' => 6,
            ],
            [
                'movie_id' => 5,
                'actor_id' => 7,
            ],
            [
                'movie_id' => 5,
                'actor_id' => 8,
            ],
        ];

        // Inserim les relacions a la taula de relació movie_actor
        foreach ($relations as $relation) {
            DB::table('movie_actor')->insert([
                'movie_id' => $relation['movie_id'],
                'actor_id' => $relation['actor_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
