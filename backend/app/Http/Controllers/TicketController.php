<?php

// app/Http/Controllers/TicketController.php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use App\Models\Screening;
use App\Models\Seat;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Mostra una llista de totes les entrades.
     */
    public function index()
    {
        $tickets = Ticket::with(['user', 'screening', 'seat'])->get(); // Obté totes les entrades amb les relacions
        return response()->json($tickets); // Retorna la llista d'entrades en format JSON
    }

    /**
     * Mostra una entrada específica.
     */
    public function show($id)
    {
        $ticket = Ticket::with(['user', 'screening', 'seat'])->find($id); // Busca l'entrada per ID amb les relacions
        if (!$ticket) {
            return response()->json(['message' => 'Entrada no trobada'], 404); // Retorna error si no es troba
        }
        return response()->json($ticket); // Retorna l'entrada en format JSON
    }

    /**
     * Crea una nova entrada.
     */
    public function store(Request $request)
    {
        // Valida les dades de la sol·licitud
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'screening_id' => 'required|exists:screenings,id',
            'seat_id' => 'required|exists:seats,id',
        ]);

        // Obté la butaca i la sessió
        $seat = Seat::find($request->seat_id);
        $screening = Screening::find($request->screening_id);

        // Comprova si la butaca ja està ocupada
        if ($seat->is_occupied) {
            return response()->json(['message' => 'Aquesta butaca ja està ocupada'], 400);
        }

        // Calcula el preu de l'entrada
        $price = $this->calculateTicketPrice($seat, $screening);

        // Crea l'entrada
        $ticket = Ticket::create([
            'user_id' => $request->user_id,
            'screening_id' => $request->screening_id,
            'seat_id' => $request->seat_id,
            'price' => $price,
        ]);

        // Marca la butaca com a ocupada
        $seat->is_occupied = true;
        $seat->save();

        return response()->json($ticket, 201); // Retorna l'entrada creada en format JSON
    }

    /**
     * Actualitza una entrada existent.
     */
    public function update(Request $request, $id)
    {
        $ticket = Ticket::find($id); // Busca l'entrada per ID
        if (!$ticket) {
            return response()->json(['message' => 'Entrada no trobada'], 404); // Retorna error si no es troba
        }

        // Valida les dades de la sol·licitud
        $request->validate([
            'user_id' => 'sometimes|exists:users,id',
            'screening_id' => 'sometimes|exists:screenings,id',
            'seat_id' => 'sometimes|exists:seats,id',
        ]);

        // Actualitza l'entrada
        if ($request->has('user_id')) {
            $ticket->user_id = $request->user_id;
        }
        if ($request->has('screening_id')) {
            $ticket->screening_id = $request->screening_id;
        }
        if ($request->has('seat_id')) {
            $ticket->seat_id = $request->seat_id;
        }
        $ticket->save();

        return response()->json($ticket); // Retorna l'entrada actualitzada en format JSON
    }

    /**
     * Elimina una entrada.
     */
    public function destroy($id)
    {
        $ticket = Ticket::find($id); // Busca l'entrada per ID
        if (!$ticket) {
            return response()->json(['message' => 'Entrada no trobada'], 404); // Retorna error si no es troba
        }

        // Marca la butaca com a disponible
        $seat = Seat::find($ticket->seat_id);
        $seat->is_occupied = false;
        $seat->save();

        $ticket->delete(); // Elimina l'entrada
        return response()->json(['message' => 'Entrada eliminada correctament']); // Retorna missatge d'èxit
    }

    /**
     * Calcula el preu de l'entrada.
     */
    private function calculateTicketPrice(Seat $seat, Screening $screening)
    {
        $basePrice = $seat->is_vip ? 8.00 : 6.00; // Preu base segons si és VIP o no
        if ($screening->is_special_day) {
            return $seat->is_vip ? 6.00 : 4.00; // Preu reduït en dia de l'espectador
        }
        return $basePrice;
    }
}
