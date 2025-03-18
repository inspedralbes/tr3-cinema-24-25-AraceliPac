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
                'date' => $screening->date, // Fecha de la sesión
                'time' => $screening->time, // Hora de la sesión
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

            // Guardar el QR en el sistema de archivos (opcional)
            $qrImagePath = 'qrcodes/ticket_' . $ticket->id . '.png';
            $result->saveToFile(public_path($qrImagePath));

            // Guardar la ruta del QR en la base de datos
            $ticket->qr_code = $qrImagePath;
            $ticket->save();

            // Marcar la butaca como ocupada
            $seat->is_occupied = true;
            $seat->save();

            // Cargar relaciones para el PDF
            $ticket->load(['screening.movie', 'seat', 'user']);

            // Generar el PDF con el QR
            $pdf = PDF::loadView('tickets.pdf', [
                'ticket' => $ticket,
                'qrCode' => $qrCodeBase64 // Pasar el QR en base64
            ]);

            // Guardar el PDF temporalmente
            $pdfPath = storage_path('app/public/tickets/ticket_' . $ticket->id . '.pdf');
            $pdfDirectory = dirname($pdfPath);

            if (!file_exists($pdfDirectory)) {
                mkdir($pdfDirectory, 0755, true);
            }

            $pdf->save($pdfPath);

            // Enviar el PDF por correo
            try {
                Mail::send('emails.tickets.purchased', ['ticket' => $ticket], function ($message) use ($ticket, $pdfPath, $user) {
                    $message->to($user->email, $user->name)
                        ->subject('Tu Entrada para ' . $ticket->screening->movie->title);

                    // Adjuntar el PDF al correo
                    $message->attach($pdfPath, [
                        'as' => 'Entrada_' . $ticket->ticket_number . '.pdf',
                        'mime' => 'application/pdf',
                    ]);
                });

                Log::info('Correo enviado correctamente a: ' . $user->email);
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
