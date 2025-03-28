<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Movie;
use App\Models\Screening;
use App\Models\Seat;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TicketController extends Controller
{
    /**
     * Verifica el acceso de administrador
     */
    protected function checkAdminAccess()
    {
        if (!Auth::check()) {
            return false;
        }

        if (Auth::user()->role_id != 1) {
            return false;
        }

        return true;
    }

    /**
     * Mostrar el listado de entradas
     */
    public function index(Request $request)
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

        // Cargar datos para filtros
        $users = User::orderBy('name')->get();
        $movies = Movie::orderBy('title')->get();

        // Comenzar la consulta
        $query = Ticket::with(['user', 'screening.movie', 'seat']);

        // Aplicar filtros si se proporcionan
        if ($request->filled('ticket_number')) {
            $query->where('ticket_number', 'like', '%' . $request->ticket_number . '%');
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('movie_id')) {
            $query->whereHas('screening', function ($q) use ($request) {
                $q->whereHas('movie', function ($q) use ($request) {
                    $q->where('id', $request->movie_id);
                });
            });
        }

        // Obtener los resultados paginados
        $tickets = $query->orderBy('created_at', 'desc')->paginate(10);

        // Mantener los parámetros de filtro en la paginación
        $tickets->appends($request->all());

        return view('admin.tickets.index', compact('tickets', 'users', 'movies'));
    }

    /**
     * Mostrar el formulario para crear una nueva entrada
     */
    public function create()
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

        $users = User::all();
        $screenings = Screening::with('movie')->get();

        // Filtrar los asientos solo cuando se haya seleccionado una proyección
        $seats = Seat::whereIn('screening_id', $screenings->pluck('id'))->get();

        return view('admin.tickets.create', compact('users', 'screenings', 'seats'));
    }

    /**
     * Almacenar una nueva entrada
     */
    public function store(Request $request)
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

        Log::info('Datos recibidos para crear ticket:', $request->all());

        // Validar los datos de la solicitud
        $validated = $request->validate([
            'ticket_number' => 'required|string|max:20|unique:tickets',
            'user_id' => 'required|exists:users,id',
            'screening_id' => 'required|exists:screenings,id',
            'seat_id' => 'required|exists:seats,id',
            'price' => 'required|numeric|min:0',
        ]);

        try {
            Log::info('Validación completada con éxito');

            // Verificar que el asiento no esté ya ocupado para esta proyección
            $existingTicket = Ticket::where('screening_id', $request->screening_id)
                ->where('seat_id', $request->seat_id)
                ->first();

            Log::info('Verificación de asiento ocupación:', ['existing_ticket' => $existingTicket]);

            if ($existingTicket) {
                return redirect()->back()
                    ->with('error', 'Aquest seient ja està ocupat per a aquesta projecció.')
                    ->withInput();
            }

            // Verificar que el asiento existe y pertenece a esta proyección
            $seat = Seat::findOrFail($request->seat_id);
            Log::info('Asiento encontrado:', ['seat' => $seat]);

            Log::info('Comparando asiento con proyección:', [
                'seat_screening_id' => $seat->screening_id,
                'request_screening_id' => $request->screening_id
            ]);

            if ($seat->screening_id != $request->screening_id) {
                return redirect()->back()
                    ->with('error', 'El seient no pertany a aquesta projecció.')
                    ->withInput();
            }

            // Marcar el asiento como ocupado
            $seat->update(['is_occupied' => true]);
            Log::info('Se marcó el asiento como ocupado');

            // Generar código QR para el ticket
            $ticketData = json_encode([
                'number' => $request->ticket_number,
                'user' => $request->user_id,
                'screening' => $request->screening_id,
                'seat' => $request->seat_id,
                'created' => now()->timestamp
            ]);

            Log::info('Datos del ticket para QR:', ['ticketData' => $ticketData]);

            // Generamos un código QR básico (más adelante podrías integrarlo con una librería)
            $qrCode = 'qr_' . Str::slug($request->ticket_number);
            Log::info('Código QR generado:', ['qr_code' => $qrCode]);

            // Crear la entrada
            Log::info('Intentando crear ticket con datos:', [
                'ticket_number' => $request->ticket_number,
                'qr_code' => $qrCode,
                'user_id' => $request->user_id,
                'screening_id' => $request->screening_id,
                'seat_id' => $request->seat_id,
                'price' => $request->price,
            ]);

            $ticket = Ticket::create([
                'ticket_number' => $request->ticket_number,
                'qr_code' => $qrCode,
                'user_id' => $request->user_id,
                'screening_id' => $request->screening_id,
                'seat_id' => $request->seat_id,
                'price' => $request->price,
            ]);

            Log::info('Ticket creado correctamente:', ['ticket_id' => $ticket->id]);

            return redirect()->route('admin.tickets')
                ->with('success', 'Entrada creada amb èxit.');
        } catch (\Throwable $e) {
            Log::error('Error al crear ticket: ' . $e->getMessage(), [
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'exception' => $e,
                'request' => $request->all()
            ]);

            return redirect()->back()
                ->with('error', 'Ha ocorregut un error: ' . $e->getMessage())
                ->withInput();
        }
    }


    /**
     * Mostrar detalles de una entrada
     */
    public function show($id)
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

        $ticket = Ticket::with(['user', 'screening.movie', 'seat'])->findOrFail($id);
        return view('admin.tickets.show', compact('ticket'));
    }

    /**
     * Mostrar formulario para editar una entrada
     */
    public function edit($id)
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

        $ticket = Ticket::with(['screening', 'seat'])->findOrFail($id);
        $users = User::orderBy('name')->get();
        $screenings = Screening::with('movie')->orderBy('screening_date')->orderBy('screening_time')->get();

        // Filtrar los asientos para mostrar solo los disponibles o el actual
        $seats = Seat::where('screening_id', $ticket->screening_id)
            ->where(function ($query) use ($ticket) {
                $query->where('is_occupied', false)
                    ->orWhere('id', $ticket->seat_id);
            })
            ->orderBy('row')
            ->orderBy('number')
            ->get();

        return view('admin.tickets.edit', compact('ticket', 'users', 'screenings', 'seats'));
    }

    /**
     * Actualizar una entrada existente
     */
    public function update(Request $request, $id)
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

        $ticket = Ticket::findOrFail($id);

        // Validar los datos de la solicitud
        $validated = $request->validate([
            'ticket_number' => 'required|string|max:20|unique:tickets,ticket_number,' . $id,
            'user_id' => 'required|exists:users,id',
            'screening_id' => 'required|exists:screenings,id',
            'seat_id' => 'required|exists:seats,id',
            'price' => 'required|numeric|min:0',
        ]);

        try {
            // Verificar cambio de asiento
            if ($request->seat_id != $ticket->seat_id || $request->screening_id != $ticket->screening_id) {
                // Verificar que el nuevo asiento no esté ocupado
                $existingTicket = Ticket::where('screening_id', $request->screening_id)
                    ->where('seat_id', $request->seat_id)
                    ->where('id', '!=', $id)
                    ->first();

                if ($existingTicket) {
                    return redirect()->back()
                        ->with('error', 'Aquest seient ja està ocupat per a aquesta projecció.')
                        ->withInput();
                }

                // Liberar el asiento anterior
                $oldSeat = Seat::find($ticket->seat_id);
                if ($oldSeat) {
                    $oldSeat->update(['is_occupied' => false]);
                }

                // Ocupar el nuevo asiento
                $newSeat = Seat::find($request->seat_id);
                if ($newSeat) {
                    $newSeat->update(['is_occupied' => true]);
                }
            }

            // Actualizar el código QR si es necesario
            $qrCode = $ticket->qr_code;
            if ($request->ticket_number != $ticket->ticket_number) {
                $qrCode = 'qr_' . Str::slug($request->ticket_number);
            }

            // Actualizar la entrada
            $ticket->update([
                'ticket_number' => $request->ticket_number,
                'qr_code' => $qrCode,
                'user_id' => $request->user_id,
                'screening_id' => $request->screening_id,
                'seat_id' => $request->seat_id,
                'price' => $request->price,
            ]);

            return redirect()->route('admin.tickets')
                ->with('success', 'Entrada actualitzada amb èxit.');
        } catch (\Exception $e) {
            Log::error('Error al actualizar ticket: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all()
            ]);

            return redirect()->back()
                ->with('error', 'Ha ocorregut un error: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Eliminar una entrada
     */
    public function destroy($id)
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

        try {
            $ticket = Ticket::findOrFail($id);

            // Liberar el asiento
            $seat = Seat::find($ticket->seat_id);
            if ($seat) {
                $seat->update(['is_occupied' => false]);
            }

            // Eliminar el ticket
            $ticket->delete();

            return redirect()->route('admin.tickets')
                ->with('success', 'Entrada eliminada amb èxit.');
        } catch (\Exception $e) {
            Log::error('Error al eliminar ticket: ' . $e->getMessage(), [
                'exception' => $e,
                'ticket_id' => $id
            ]);

            return redirect()->route('admin.tickets')
                ->with('error', 'No s\'ha pogut eliminar l\'entrada: ' . $e->getMessage());
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

    /**
     * Genera un PDF con la entrada
     */
    public function downloadPdf($id)
    {
        if (!$this->checkAdminAccess()) {
            return redirect('/login');
        }

        try {
            $ticket = Ticket::with(['user', 'screening.movie', 'seat'])->findOrFail($id);

            // Generar PDF (requiere instalación de paquete laravel-dompdf)
            // $pdf = PDF::loadView('admin.tickets.pdf', compact('ticket'));

            // return $pdf->download('entrada-' . $ticket->ticket_number . '.pdf');

            // Como alternativa, podemos devolver una vista por ahora
            return view('admin.tickets.pdf', compact('ticket'));
        } catch (\Exception $e) {
            Log::error('Error generando PDF: ' . $e->getMessage(), [
                'exception' => $e,
                'ticket_id' => $id
            ]);

            return redirect()->route('admin.tickets')
                ->with('error', 'No s\'ha pogut generar el PDF: ' . $e->getMessage());
        }
    }
}
