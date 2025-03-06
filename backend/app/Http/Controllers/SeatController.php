<?php

// app/Http/Controllers/SeatController.php

namespace App\Http\Controllers;

use App\Models\Seat;
use App\Models\Screening;
use Illuminate\Http\Request;

class SeatController extends Controller
{
    /**
     * Mostra una llista de totes les butaques per a una sessió.
     */
    public function index($screeningId)
    {
        $seats = Seat::where('screening_id', $screeningId)->get(); // Obté totes les butaques per a la sessió
        return response()->json($seats); // Retorna la llista de butaques en format JSON
    }

    /**
     * Mostra una butaca específica.
     */
    public function show($id)
    {
        $seat = Seat::find($id); // Busca la butaca per ID
        if (!$seat) {
            return response()->json(['message' => 'Butaca no trobada'], 404); // Retorna error si no es troba
        }
        return response()->json($seat); // Retorna la butaca en format JSON
    }

    /**
     * Crea una nova butaca.
     */
    public function store(Request $request)
    {
        // Valida les dades de la sol·licitud
        $request->validate([
            'screening_id' => 'required|exists:screenings,id',
            'row' => 'required|string|size:1|in:A,B,C,D,E,F,G,H,I,J,K,L', // Files de A a L
            'number' => 'required|integer|between:1,10', // Números de 1 a 10
            'is_vip' => 'boolean',
            'is_occupied' => 'boolean',
        ]);

        // Comprova si la butaca ja existeix per a aquesta sessió
        $existingSeat = Seat::where('screening_id', $request->screening_id)
            ->where('row', $request->row)
            ->where('number', $request->number)
            ->exists();

        if ($existingSeat) {
            return response()->json(['message' => 'Aquesta butaca ja existeix per a aquesta sessió'], 400);
        }

        // Crea la butaca
        $seat = Seat::create([
            'screening_id' => $request->screening_id,
            'row' => $request->row,
            'number' => $request->number,
            'is_vip' => $request->is_vip ?? false,
            'is_occupied' => $request->is_occupied ?? false,
        ]);

        return response()->json($seat, 201); // Retorna la butaca creada en format JSON
    }

    /**
     * Actualitza una butaca existent.
     */
    public function update(Request $request, $id)
    {
        $seat = Seat::find($id); // Busca la butaca per ID
        if (!$seat) {
            return response()->json(['message' => 'Butaca no trobada'], 404); // Retorna error si no es troba
        }

        // Valida les dades de la sol·licitud
        $request->validate([
            'is_vip' => 'sometimes|boolean',
            'is_occupied' => 'sometimes|boolean',
        ]);

        // Actualitza la butaca
        if ($request->has('is_vip')) {
            $seat->is_vip = $request->is_vip;
        }
        if ($request->has('is_occupied')) {
            $seat->is_occupied = $request->is_occupied;
        }
        $seat->save();

        return response()->json($seat); // Retorna la butaca actualitzada en format JSON
    }

    /**
     * Elimina una butaca.
     */
    public function destroy($id)
    {
        $seat = Seat::find($id); // Busca la butaca per ID
        if (!$seat) {
            return response()->json(['message' => 'Butaca no trobada'], 404); // Retorna error si no es troba
        }

        $seat->delete(); // Elimina la butaca
        return response()->json(['message' => 'Butaca eliminada correctament']); // Retorna missatge d'èxit
    }
}
