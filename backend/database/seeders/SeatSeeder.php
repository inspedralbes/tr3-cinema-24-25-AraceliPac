<?php

// database/seeders/SeatSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Seat;
use App\Models\Screening;

class SeatSeeder extends Seeder
{
    /**
     * Ejecuta el seeder.
     */
    public function run()
    {
        // Obtenemos todas las sesiones de la base de datos
        $screenings = Screening::all();

        // Define las filas (A-L) y los números de asiento (1-10)
        $rows = range('A', 'L');
        $numbers = range(1, 10);

        // Para cada sesión, crea los asientos correspondientes
        foreach ($screenings as $screening) {
            // Opcional: Si ya existen asientos para esta sesión, se puede saltar para evitar duplicados
            if ($screening->seats()->count() > 0) {
                continue;
            }

            foreach ($rows as $row) {
                foreach ($numbers as $number) {
                    Seat::create([
                        'screening_id' => $screening->id,
                        'row' => $row,
                        'number' => $number,
                        // Se considera VIP si la fila es 'F'
                        'is_vip' => ($row === 'F'),
                        'is_occupied' => false, // Por defecto, el asiento no está ocupado
                    ]);
                }
            }
        }
    }
}
