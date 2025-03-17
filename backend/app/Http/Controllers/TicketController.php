<?php

// app/Http/Controllers/TicketController.php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use App\Models\Screening;
use App\Models\Seat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;

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
        $screening = Screening::with(['movie'])->find($request->screening_id);
        $user = User::find(Auth::id());

        // Comprova si la butaca ja està ocupada
        if ($seat->is_occupied) {
            return response()->json(['message' => 'Aquesta butaca ja està ocupada'], 400);
        }

        // Genera un número de ticket únic
        $ticketNumber = 'TK' . $screening->id . now()->format('Ymd') . rand(1000, 9999);

        // Calcula el preu de l'entrada
        $price = $this->calculateTicketPrice($seat, $screening);

        // Crea l'entrada
        $ticket = Ticket::create([
            'user_id' => Auth::id(),
            'screening_id' => $request->screening_id,
            'seat_id' => $request->seat_id,
            'price' => $price,
            'ticket_number' => $ticketNumber,
        ]);

        try {
            // Para versiones más antiguas de Simple QRCode, usamos una solución sin QR primero
            // Marcamos que el ticket fue creado correctamente
            $ticket->qr_code = null; // De momento no generamos QR
            $ticket->save();

            // Marca la butaca com a ocupada
            $seat->is_occupied = true;
            $seat->save();

            // Cargar completamente el ticket para el PDF
            $ticket->load(['screening.movie', 'seat', 'user']);

            // Crear PDF sin QR por ahora
            $pdf = PDF::loadView('tickets.pdf', [
                'ticket' => $ticket,
                'qrCode' => null // No incluimos QR por ahora
            ]);

            // Guardar PDF temporalmente
            $pdfPath = storage_path('app/public/tickets/ticket_' . $ticket->id . '.pdf');
            $pdfDirectory = dirname($pdfPath);

            if (!file_exists($pdfDirectory)) {
                mkdir($pdfDirectory, 0755, true);
            }

            $pdf->save($pdfPath);

            // Intentamos enviar correo sin adjunto de PDF
            try {
                Mail::send('emails.tickets.purchased', ['ticket' => $ticket], function ($message) use ($ticket, $user) {
                    $message->to($user->email, $user->name)
                        ->subject('Tu Entrada para ' . $ticket->screening->movie->title);
                    // No adjuntamos PDF por ahora
                });
                Log::info('Correo enviado correctamente a: ' . $user->email);
            } catch (\Exception $e) {
                Log::error('Error al enviar correo: ' . $e->getMessage());
            }
        } catch (\Exception $e) {
            // Registra el error pero permite que el ticket se cree
            Log::error('Error en el proceso: ' . $e->getMessage());
            Log::error('Archivo: ' . $e->getFile() . ' en línea: ' . $e->getLine());
        }

        return response()->json($ticket, 201);
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

    public function availableSeats($screeningId)
    {
        $screening = Screening::findOrFail($screeningId);

        // Obtener todos los asientos
        $allSeats = Seat::all(); // Obtenemos todos los asientos sin filtrar por sala

        // Obtener los asientos ya ocupados para esta proyección
        $occupiedSeatIds = Ticket::where('screening_id', $screeningId)
            ->pluck('seat_id')
            ->toArray();

        // Marcar cada asiento como disponible o no
        $seats = $allSeats->map(function ($seat) use ($occupiedSeatIds) {
            $seat->is_available = !in_array($seat->id, $occupiedSeatIds);
            return $seat;
        });

        return response()->json([
            'seats' => $seats
        ]);
    }


    public function showQrCode($id)
    {
        $ticket = Ticket::with(['screening.movie', 'seat'])->findOrFail($id);

        // Generamos el QR con la información del ticket
        $screening = $ticket->screening;
        $seat = $ticket->seat;

        $qrData = json_encode([
            'id' => $ticket->id,
            'ticket_number' => $ticket->ticket_number,
            'screening_id' => $screening->id,
            'movie' => $screening->movie->title,
            'date' => $screening->date,
            'time' => $screening->time,
            'seat' => $seat->row . $seat->number
        ]);

        // Generamos el QR sin métodos específicos para backend
        return response(
            QrCode::format('png')
                ->size(300)
                ->generate($qrData)
        )->header('Content-Type', 'image/png');
    }

    public function downloadTicketPdf($id)
    {
        $ticket = Ticket::with(['user', 'screening.movie', 'seat'])->findOrFail($id);

        // Generar los datos para el QR en caso de necesitarlos dentro del PDF
        $qrData = json_encode([
            'id' => $ticket->id,
            'ticket_number' => $ticket->ticket_number,
            'screening_id' => $ticket->screening->id,
            'movie' => $ticket->screening->movie->title,
            'date' => $ticket->screening->date,
            'time' => $ticket->screening->time,
            'seat' => $ticket->seat->row . $ticket->seat->number
        ]);

        // Generar el código QR como imagen base64 para incrustarlo en el PDF
        $qrCode = base64_encode(
            QrCode::format('png')
                ->useBackend('bacon')  // Usar bacon backend
                ->size(200)
                ->generate($qrData)
        );

        $pdf = PDF::loadView('tickets.pdf', [
            'ticket' => $ticket,
            'qrCode' => $qrCode
        ]);

        return $pdf->download('entrada_' . $ticket->ticket_number . '.pdf');
    }
}
