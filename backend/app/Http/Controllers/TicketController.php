<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use App\Models\Screening;
use App\Models\Seat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;

class TicketController extends Controller
{
    /**
     * Crea una nova entrada.
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
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'screening_id' => 'required|exists:screenings,id',
            'seat_id' => 'required|exists:seats,id',
        ]);

        $seat = Seat::find($request->seat_id);
        $screening = Screening::with(['movie'])->find($request->screening_id);
        $user = User::find(Auth::id());

        if ($seat->is_occupied) {
            return response()->json(['message' => 'Aquesta butaca ja està ocupada'], 400);
        }

        // Generar un número de ticket único
        $ticketNumber = 'TK' . $screening->id . now()->format('Ymd') . rand(1000, 9999);

        // Calcular el precio del ticket
        $price = $this->calculateTicketPrice($seat, $screening);

        // Crear el ticket
        $ticket = Ticket::create([
            'user_id' => Auth::id(),
            'screening_id' => $request->screening_id,
            'seat_id' => $request->seat_id,
            'price' => $price,
            'ticket_number' => $ticketNumber,
        ]);

        try {
            // Datos para el QR (usar la fecha y hora de la sesión, no de la compra)
            $qrData = json_encode([
                'id' => $ticket->id,
                'ticket_number' => $ticketNumber,
                'screening_id' => $screening->id,
                'movie' => $screening->movie->title,
                'date' => $screening->screening_date, // Nombre correcto del campo de fecha
                'time' => $screening->screening_time, // Nombre correcto del campo de hora
                'seat' => $seat->row . $seat->number
            ]);

            // Generar el QR con endroid/qr-code
            $qrCode = new QrCode($qrData);
            $qrCode->setSize(300);
            $qrCode->setMargin(10);
            $qrCode->setForegroundColor(new Color(0, 0, 0));
            $qrCode->setBackgroundColor(new Color(255, 255, 255));
            $qrCode->setEncoding(new Encoding('UTF-8'));

            $writer = new PngWriter();
            $result = $writer->write($qrCode);

            // Convertir el QR a base64 para incrustarlo en el PDF
            $qrCodeBase64 = $result->getDataUri();

            // NO guardar el QR en el sistema de archivos - solo lo usar para el PDF
            // $qrImagePath = 'qrcodes/ticket_' . $ticket->id . '.png';
            // $result->saveToFile(public_path($qrImagePath));
            // $ticket->qr_code = $qrImagePath;
            // $ticket->save();

            // Marcar la butaca como ocupada
            $seat->is_occupied = true;
            $seat->save();

            // Cargar relaciones para el PDF
            $ticket->load(['screening.movie', 'seat', 'user']);
            $screeningDate = \Carbon\Carbon::parse($ticket->screening->date)->format('d/m/Y');

            // Asegurarnos que la hora se formatea correctamente estableciendo la zona horaria
            $screeningTime = \Carbon\Carbon::parse($ticket->screening->time)
                ->setTimezone('Europe/Madrid') // Ajusta esto a tu zona horaria
                ->format('H:i');
            // Generar el PDF con el QR
            $pdf = PDF::loadView('tickets.pdf', [
                'ticket' => $ticket,
                'qrCode' => $qrCodeBase64 // Pasar el QR en base64
            ]);

            // Configurar opciones para solucionar problemas de formato
            $pdf->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'dpi' => 150,
                'defaultFont' => 'sans-serif',
                'isPhpEnabled' => true
            ]);

            // Guardar el PDF temporalmente con codificación binaria
            $pdfPath = storage_path('tickets/ticket_' . $ticket->ticket_number . '.pdf');
            $pdfDirectory = dirname($pdfPath);

            if (!file_exists($pdfDirectory)) {
                mkdir($pdfDirectory, 0755, true);
            }

            // Guardar directamente como binario
            $pdf->save($pdfPath);

            // Enviar el PDF por correo
            try {
                Mail::send(
                    'emails.tickets.purchased', 
                    [
                        'ticket' => $ticket,
                        'screeningDate' => $screeningDate,
                        'screeningTime' => $screeningTime
                    ], 
                    function ($message) use ($ticket, $pdfPath, $user) {
                        $message->to($user->email, $user->name)
                            ->subject('La teva Entrada per ' . $ticket->screening->movie->title);
            
                        // Adjuntar el PDF al correo
                        $message->attach($pdfPath, [
                            'as' => 'Entrada_' . $ticket->ticket_number . '.pdf',
                            'mime' => 'application/pdf',
                        ]);
                    } 
                );

                Log::info('Correo enviat correctament a: ' . $user->email);

                $ticket->save();
            } catch (\Exception $e) {
                Log::error('Error al enviar correo: ' . $e->getMessage());
            }
        } catch (\Exception $e) {
            Log::error('Error en el proceso: ' . $e->getMessage());
            Log::error('Archivo: ' . $e->getFile() . ' en línea: ' . $e->getLine());
        }

        return response()->json($ticket, 201);
    }

    /**
     * Calcula el preu de l'entrada.
     */
    private function calculateTicketPrice(Seat $seat, Screening $screening)
    {
        $basePrice = $seat->is_vip ? 8.00 : 6.00;
        if ($screening->is_special_day) {
            return $seat->is_vip ? 6.00 : 4.00;
        }
        return $basePrice;
    }
}
