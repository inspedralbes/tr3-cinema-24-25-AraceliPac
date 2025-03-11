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
     * Ejecuta el seeder.
     */
    public function run()
    {
        // Define los horarios de sesión
        $screeningTimes = ['16:00', '18:00', '20:00'];
        // Define para cuántos días (a partir de mañana) se quiere crear las sesiones.
        $days = [1, 2, 3];

        // Obtiene todas las películas de la base de datos
        $movies = Movie::all();

        // Para cada película y para cada día, crea las 3 sesiones
        foreach ($movies as $movie) {
            foreach ($days as $dayOffset) {
                // Calcula la fecha de la sesión a partir de hoy (como instancia Carbon)
                $screeningDate = Carbon::today()->addDays($dayOffset);
                
                foreach ($screeningTimes as $time) {
                    Screening::create([
                        'movie_id'       => $movie->id,
                        'screening_date' => $screeningDate->toDateString(),
                        'screening_time' => $time,
                        // Si la fecha es miércoles se marca como día especial
                        'is_special_day' => $screeningDate->isWednesday(),
                    ]);
                }
            }
        }
    }
}