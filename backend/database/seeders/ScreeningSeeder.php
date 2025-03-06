<?php

// database/seeders/ScreeningSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Screening;
use App\Models\Movie;
use Carbon\Carbon;

class ScreeningSeeder extends Seeder
{
    /**
     * Executa el seeder.
     */
    public function run()
    {
        // Defineix les sessions inicials
        $screenings = [
            [
                'movie_id' => Movie::where('title', 'Capitán América: Brave New World')->first()->id,
                'screening_date' => Carbon::today()->addDays(1)->toDateString(), // Demà
                'screening_time' => '16:00',
                'is_special_day' => false,
            ],
            [
                'movie_id' => Movie::where('title', 'Bridget Jones: Loca per ell')->first()->id,
                'screening_date' => Carbon::today()->addDays(1)->toDateString(), // Demà
                'screening_time' => '18:00',
                'is_special_day' => false,
            ],
            [
                'movie_id' => Movie::where('title', 'Paddington: Aventura en la selva')->first()->id,
                'screening_date' => Carbon::today()->addDays(2)->toDateString(), // Demà passat
                'screening_time' => '20:00',
                'is_special_day' => true, // Dia de l'espectador
            ],
            [
                'movie_id' => Movie::where('title', 'El secret del orfebre')->first()->id,
                'screening_date' => Carbon::today()->addDays(3)->toDateString(), // 3 dies
                'screening_time' => '16:00',
                'is_special_day' => false,
            ],
            [
                'movie_id' => Movie::where('title', 'The Monkey')->first()->id,
                'screening_date' => Carbon::today()->addDays(3)->toDateString(), // 3 dies
                'screening_time' => '18:00',
                'is_special_day' => false,
            ],
        ];

        // Insereix les sessions a la base de dades
        foreach ($screenings as $screening) {
            Screening::create($screening);
        }
    }
}
