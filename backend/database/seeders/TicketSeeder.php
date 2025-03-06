<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Screening;
use App\Models\Seat;

class TicketSeeder extends Seeder
{
    /**
     * Ejecuta el seeder.
     */
    public function run()
    {
        // Obtenemos el primer usuario y la primera sesión disponibles
        $user = User::first();
        $screening = Screening::find(6);

        // Si no existe un usuario o una sesión, lanzamos una excepción
        if (!$user || !$screening) {
            throw new \Exception('No se ha encontrado un usuario o una sesión para generar entradas.');
        }

        // Intentamos obtener un asiento asociado a la sesión
        $seat = Seat::where('screening_id', $screening->id)->first();

        // Si no se encuentra un asiento, lo creamos
        if (!$seat) {
            $seat = Seat::create([
                'screening_id' => $screening->id,
                'is_vip' => false, // Puedes modificar esto según tu lógica (true/false)
            ]);
        }

        // Calculamos el precio de la entrada
        $price = $this->calculateTicketPrice($seat, $screening);

        // Creamos una entrada
        Ticket::create([
            'user_id' => $user->id,
            'screening_id' => $screening->id,
            'seat_id' => $seat->id,
            'price' => $price,
        ]);
    }

    /**
     * Calcula el precio de la entrada (misma lógica que en el controlador).
     */
    private function calculateTicketPrice(Seat $seat, Screening $screening)
    {
        // Precios base
        $normalPrice = 6.00;
        $vipPrice = 8.00;

        // Precios reducidos en día de espectador
        $specialDayNormalPrice = 4.00;
        $specialDayVipPrice = 6.00;

        // Verificamos si es un día especial
        if ($screening->is_special_day) {
            return $seat->is_vip ? $specialDayVipPrice : $specialDayNormalPrice;
        }

        // Si no es día especial, retornamos el precio normal
        return $seat->is_vip ? $vipPrice : $normalPrice;
    }
}
