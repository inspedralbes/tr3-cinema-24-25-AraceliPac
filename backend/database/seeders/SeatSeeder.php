<?php

// database/seeders/SeatSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Seat;
use App\Models\Screening;
use Carbon\Carbon;

class SeatSeeder extends Seeder
{
    /**
     * Executa el seeder.
     */
    public function run()
    {
        // Obté una sessió existent (o crea'n una si no existeix)
        $screening = Screening::firstOrCreate([
            'movie_id' => 1, // ID de la pel·lícula
            'screening_date' => Carbon::today()->toDateString(), // Avui
            'screening_time' => '16:00',
            'is_special_day' => false,
        ]);

        // Defineix les files (A-L) i les butaques (1-10)
        $rows = range('A', 'L');
        $numbers = range(1, 10);

        // Crea les butaques per a la sessió
        foreach ($rows as $row) {
            foreach ($numbers as $number) {
                Seat::create([
                    'screening_id' => $screening->id,
                    'row' => $row,
                    'number' => $number,
                    'is_vip' => ($row === 'F'), // Fila F és VIP
                    'is_occupied' => false, // Per defecte, no ocupada
                ]);
            }
        }
    }
}
