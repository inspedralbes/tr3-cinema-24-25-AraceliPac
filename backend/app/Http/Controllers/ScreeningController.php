<?php

// app/Http/Controllers/ScreeningController.php

namespace App\Http\Controllers;

use App\Models\Screening;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ScreeningController extends Controller
{
    /**
     * Mostra una llista de totes les sessions.
     */
    public function index()
    {
        $screenings = Screening::with('movie')->get(); // Obté totes les sessions amb les pel·lícules associades
        return response()->json($screenings); // Retorna la llista de sessions en format JSON
    }

    /**
     * Mostra una sessió específica.
     */
    public function show($id)
    {
        $screening = Screening::with('movie')->find($id); // Busca la sessió per ID amb la pel·lícula associada
        if (!$screening) {
            return response()->json(['message' => 'Sessió no trobada'], 404); // Retorna error si no es troba
        }
        return response()->json($screening); // Retorna la sessió en format JSON
    }

    /**
     * Crea una nova sessió.
     */
    public function store(Request $request)
    {
        // Valida les dades de la sol·licitud
        $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'screening_date' => 'required|date',
            'screening_time' => [
                'required',
                Rule::in(['16:00', '18:00', '20:00']), // Només permet aquests horaris
            ],
            'is_special_day' => 'boolean',
        ]);

        // Comprova si ja existeix una sessió per a aquest dia i hora
        $existingScreening = Screening::where('screening_date', $request->screening_date)
            ->where('screening_time', $request->screening_time)
            ->exists();

        if ($existingScreening) {
            return response()->json(['message' => 'Ja existeix una sessió per a aquest dia i hora'], 400);
        }

        // Crea la sessió
        $screening = Screening::create([
            'movie_id' => $request->movie_id,
            'screening_date' => $request->screening_date,
            'screening_time' => $request->screening_time,
            'is_special_day' => $request->is_special_day ?? false,
        ]);

        return response()->json($screening, 201); // Retorna la sessió creada en format JSON
    }

    /**
     * Actualitza una sessió existent.
     */
    public function update(Request $request, $id)
    {
        $screening = Screening::find($id); // Busca la sessió per ID
        if (!$screening) {
            return response()->json(['message' => 'Sessió no trobada'], 404); // Retorna error si no es troba
        }

        // Valida les dades de la sol·licitud
        $request->validate([
            'movie_id' => 'sometimes|exists:movies,id',
            'screening_date' => 'sometimes|date',
            'screening_time' => [
                'sometimes',
                Rule::in(['16:00', '18:00', '20:00']), // Només permet aquests horaris
            ],
            'is_special_day' => 'sometimes|boolean',
        ]);

        // Comprova si ja existeix una sessió per a aquest dia i hora
        if ($request->has('screening_date') && $request->has('screening_time')) {
            $existingScreening = Screening::where('screening_date', $request->screening_date)
                ->where('screening_time', $request->screening_time)
                ->where('id', '!=', $id) // Exclou la sessió actual
                ->exists();

            if ($existingScreening) {
                return response()->json(['message' => 'Ja existeix una sessió per a aquest dia i hora'], 400);
            }
        }

        // Actualitza la sessió
        if ($request->has('movie_id')) {
            $screening->movie_id = $request->movie_id;
        }
        if ($request->has('screening_date')) {
            $screening->screening_date = $request->screening_date;
        }
        if ($request->has('screening_time')) {
            $screening->screening_time = $request->screening_time;
        }
        if ($request->has('is_special_day')) {
            $screening->is_special_day = $request->is_special_day;
        }
        $screening->save();

        return response()->json($screening); // Retorna la sessió actualitzada en format JSON
    }

    /**
     * Elimina una sessió.
     */
    public function destroy($id)
    {
        $screening = Screening::find($id); // Busca la sessió per ID
        if (!$screening) {
            return response()->json(['message' => 'Sessió no trobada'], 404); // Retorna error si no es troba
        }

        $screening->delete(); // Elimina la sessió
        return response()->json(['message' => 'Sessió eliminada correctament']); // Retorna missatge d'èxit
    }
}
