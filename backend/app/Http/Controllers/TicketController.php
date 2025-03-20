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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

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

        // Iniciar transacción de base de datos
        DB::beginTransaction();

        try {
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

            // Marcar inmediatamente la butaca como ocupada
            $seat->is_occupied = true;
            $seat->save();

            // Confirmar la transacción de base de datos para asegurar que el ticket y el asiento se guarden
            DB::commit();

            // Log de confirmación
            Log::info('Ticket creado y asiento marcado como ocupado: Ticket ID=' . $ticket->id);

            try {
                // Datos para el QR
                $qrData = json_encode([
                    'id' => $ticket->id,
                    'ticket_number' => $ticketNumber,
                    'screening_id' => $screening->id,
                    'movie' => $screening->movie->title,
                    'date' => $screening->screening_date,
                    'time' => $screening->screening_time,
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
                $qrCodeBase64 = $result->getDataUri();

                // Cargar relaciones para el PDF
                $ticket->load(['screening.movie', 'seat', 'user']);
                $screeningDate = \Carbon\Carbon::parse($ticket->screening->date)->format('d/m/Y');
                $screeningTime = \Carbon\Carbon::parse($ticket->screening->time)
                    ->setTimezone('Europe/Madrid')
                    ->format('H:i');

                // Generar el PDF con el QR
                $pdf = PDF::loadView('tickets.pdf', [
                    'ticket' => $ticket,
                    'qrCode' => $qrCodeBase64
                ]);

                // Configurar opciones para solucionar problemas de formato
                $pdf->setOptions([
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                    'dpi' => 150,
                    'defaultFont' => 'sans-serif',
                    'isPhpEnabled' => true
                ]);

                // Obtener el PDF como string en memoria
                $pdfContent = $pdf->output();

                // SOLUCIÓN HÍBRIDA: guardar una copia en el directorio de usuario
                $userStoragePath = 'tickets'; // Ubicación relativa en storage/app/public

                // Asegurar que el directorio existe en storage/app/public
                if (!Storage::disk('public')->exists($userStoragePath)) {
                    Storage::disk('public')->makeDirectory($userStoragePath);
                }

                // Guardar el PDF usando el sistema de almacenamiento de Laravel
                $pdfStoragePath = $userStoragePath . '/ticket_' . $ticket->ticket_number . '.pdf';
                Storage::disk('public')->put($pdfStoragePath, $pdfContent);

                Log::info('PDF guardado en storage: ' . $pdfStoragePath);

                // Enviar correo con el PDF adjunto
                Mail::send(
                    'emails.tickets.purchased',
                    [
                        'ticket' => $ticket,
                        'screeningDate' => $screeningDate,
                        'screeningTime' => $screeningTime
                    ],
                    function ($message) use ($ticket, $pdfContent, $user, $ticketNumber) {
                        $message->to($user->email, $user->name)
                            ->subject('La teva Entrada per ' . $ticket->screening->movie->title);

                        // Adjuntar el PDF directamente desde la memoria
                        $message->attachData($pdfContent, 'Entrada_' . $ticketNumber . '.pdf', [
                            'mime' => 'application/pdf',
                        ]);
                    }
                );

                Log::info('Correo enviado correctamente a: ' . $user->email);
            } catch (\Exception $e) {
                // Si falla el envío del correo, solo logueamos el error
                // pero NO afecta a la creación del ticket o reserva del asiento
                Log::error('Error al generar PDF o enviar correo: ' . $e->getMessage());
                Log::error('Archivo: ' . $e->getFile() . ' en línea: ' . $e->getLine());
            }

            return response()->json($ticket, 201);
        } catch (\Exception $e) {
            // Si algo falla en la creación del ticket, revertimos los cambios
            DB::rollBack();
            Log::error('Error al crear ticket: ' . $e->getMessage());
            Log::error('Archivo: ' . $e->getFile() . ' en línea: ' . $e->getLine());

            return response()->json([
                'message' => 'Error al procesar la compra. Contacta con soporte.',
                'error' => $e->getMessage()
            ], 500);
        }
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
    // Método para descargar el PDF de un ticket
    public function downloadPdf($id)
    {
        $ticket = Ticket::findOrFail($id);
        $pdfPath = 'tickets/ticket_' . $ticket->ticket_number . '.pdf';

        // Verificar si existe en storage/app/public
        if (Storage::disk('public')->exists($pdfPath)) {
            return response()->download(storage_path('app/public/' . $pdfPath), 'Entrada_' . $ticket->ticket_number . '.pdf', [
                'Content-Type' => 'application/pdf'
            ]);
        }

        // Si no existe, intentar regenerarlo
        try {
            // Cargar relaciones necesarias
            $ticket->load(['screening.movie', 'seat', 'user']);
            $seat = $ticket->seat;
            $screening = $ticket->screening;

            // Regenerar el QR
            $qrData = json_encode([
                'id' => $ticket->id,
                'ticket_number' => $ticket->ticket_number,
                'screening_id' => $screening->id,
                'movie' => $screening->movie->title,
                'date' => $screening->screening_date,
                'time' => $screening->screening_time,
                'seat' => $seat->row . $seat->number
            ]);

            // Generar el QR
            $qrCode = new QrCode($qrData);
            $qrCode->setSize(300);
            $qrCode->setMargin(10);
            $qrCode->setForegroundColor(new Color(0, 0, 0));
            $qrCode->setBackgroundColor(new Color(255, 255, 255));
            $qrCode->setEncoding(new Encoding('UTF-8'));

            $writer = new PngWriter();
            $result = $writer->write($qrCode);
            $qrCodeBase64 = $result->getDataUri();

            // Generar el PDF
            $pdf = PDF::loadView('tickets.pdf', [
                'ticket' => $ticket,
                'qrCode' => $qrCodeBase64
            ]);

            // Configurar opciones
            $pdf->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'dpi' => 150,
                'defaultFont' => 'sans-serif',
                'isPhpEnabled' => true
            ]);

            // Guardar y devolver el PDF regenerado
            $pdfContent = $pdf->output();

            // Asegurar que el directorio existe
            if (!Storage::disk('public')->exists('tickets')) {
                Storage::disk('public')->makeDirectory('tickets');
            }

            // Guardar el PDF
            Storage::disk('public')->put($pdfPath, $pdfContent);

            Log::info('PDF regenerado para descarga: ' . $pdfPath);

            return response($pdfContent, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="Entrada_' . $ticket->ticket_number . '.pdf"',
            ]);
        } catch (\Exception $e) {
            Log::error('Error al regenerar PDF: ' . $e->getMessage());
            return response()->json(['message' => 'No se pudo generar el PDF'], 500);
        }
    }
}
