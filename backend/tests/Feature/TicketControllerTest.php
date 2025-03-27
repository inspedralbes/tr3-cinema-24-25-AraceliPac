<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Movie;
use App\Models\Genre;
use App\Models\Director;
use App\Models\Screening;
use App\Models\Seat;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\DatabaseTransactions;


class TicketControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;
    protected $movie;
    protected $screening;
    protected $seat;

    public function setUp(): void
    {
        parent::setUp();

        // Fake mail para evitar envíos reales
        Mail::fake();

        // Crear un rol para el usuario
        $role = Role::firstOrCreate(['name' => 'user']);

        // Crear un usuario
        $this->user = User::factory()->create([
            'role_id' => $role->id,
            'email' => 'test@example.com'
        ]);

        // Crear género y director
        $genre = Genre::firstOrCreate(['name' => 'Acción']);
        $director = Director::firstOrCreate(
            ['name' => 'Christopher', 'lastname' => 'Nolan'],
            ['birthdate' => '1970-07-30']
        );

        // Crear una película
        $this->movie = Movie::firstOrCreate(
            ['title' => 'Interstellar', 'release_year' => 2014],
            [
                'genre_id' => $genre->id,
                'director_id' => $director->id
            ]
        );

        // Crear una sesión
        $this->screening = Screening::firstOrCreate(
            [
                'movie_id' => $this->movie->id,
                'screening_date' => '2025-04-15',
                'screening_time' => '20:00'
            ],
            ['is_special_day' => false]
        );

        // Crear una butaca
        $this->seat = Seat::firstOrCreate(
            [
                'row' => 'A',
                'number' => 1,
                'screening_id' => $this->screening->id
            ],
            [
                'is_vip' => false,
                'is_occupied' => false
            ]
        );

        // Crear directorio para tickets si no existe
        if (!file_exists(storage_path('tickets'))) {
            mkdir(storage_path('tickets'), 0755, true);
        }
    }

    /** @test */
    public function user_can_get_their_tickets()
    {
        // Crear un ticket para el usuario
        $ticket = Ticket::create([
            'user_id' => $this->user->id,
            'screening_id' => $this->screening->id,
            'seat_id' => $this->seat->id,
            'price' => 6.00,
            'ticket_number' => 'TK123456'
        ]);

        // Autenticar al usuario
        Sanctum::actingAs($this->user);

        // Hacer la petición
        $response = $this->getJson('/api/tickets');

        // Verificar respuesta
        $response->assertStatus(200);
        $this->assertNotEmpty($response->json());
    }

    /** @test */
    public function user_can_get_a_specific_ticket()
    {
        // Crear un ticket
        $ticket = Ticket::create([
            'user_id' => $this->user->id,
            'screening_id' => $this->screening->id,
            'seat_id' => $this->seat->id,
            'price' => 6.00,
            'ticket_number' => 'TK123456'
        ]);

        // Autenticar al usuario
        Sanctum::actingAs($this->user);

        // Hacer la petición
        $response = $this->getJson('/api/tickets/' . $ticket->id);

        // Verificar respuesta
        $response->assertStatus(200);
        $response->assertJson(['id' => $ticket->id]);
    }

    /** @test */
    public function user_can_purchase_a_ticket()
    {
        // Autenticar al usuario
        Sanctum::actingAs($this->user);

        // Datos para comprar un ticket
        $ticketData = [
            'user_id' => $this->user->id,
            'screening_id' => $this->screening->id,
            'seat_id' => $this->seat->id
        ];

        // Hacer la petición
        $response = $this->postJson('/api/tickets', $ticketData);

        // Verificar respuesta
        $response->assertStatus(201);

        // Verificar que se ha creado el ticket en la base de datos
        $this->assertDatabaseHas('tickets', [
            'user_id' => $this->user->id,
            'screening_id' => $this->screening->id,
            'seat_id' => $this->seat->id
        ]);
    }

    /** @test */
    public function user_can_delete_their_ticket()
    {
        // Crear un ticket
        $ticket = Ticket::create([
            'user_id' => $this->user->id,
            'screening_id' => $this->screening->id,
            'seat_id' => $this->seat->id,
            'price' => 6.00,
            'ticket_number' => 'TK123456'
        ]);

        // Marcar la butaca como ocupada
        $this->seat->is_occupied = true;
        $this->seat->save();

        // Autenticar al usuario
        Sanctum::actingAs($this->user);

        // Hacer la petición
        $response = $this->deleteJson('/api/tickets/' . $ticket->id);

        // Verificar respuesta
        $response->assertStatus(200);

        // Verificar que el ticket se eliminó
        $this->assertDatabaseMissing('tickets', ['id' => $ticket->id]);

        // Verificar que la butaca está libre de nuevo
        // El asiento debería reflejar el cambio en la base de datos
        $seat = Seat::find($this->seat->id);
        $this->assertEquals(0, $seat->is_occupied);
    }

    /** @test */
    public function user_can_update_ticket()
    {
        // Crear un asiento alternativo
        $alternateSeat = Seat::create([
            'row' => 'B',
            'number' => 5,
            'is_vip' => false,
            'is_occupied' => false,
            'screening_id' => $this->screening->id
        ]);

        // Crear un ticket
        $ticket = Ticket::create([
            'user_id' => $this->user->id,
            'screening_id' => $this->screening->id,
            'seat_id' => $this->seat->id,
            'price' => 6.00,
            'ticket_number' => 'TK123456'
        ]);

        // Autenticar al usuario
        Sanctum::actingAs($this->user);

        // Datos para actualizar
        $updateData = [
            'seat_id' => $alternateSeat->id
        ];

        // Hacer la petición
        $response = $this->putJson('/api/tickets/' . $ticket->id, $updateData);

        // Verificar respuesta
        $response->assertStatus(200);

        // Verificar que el ticket se actualizó
        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'seat_id' => $alternateSeat->id
        ]);
    }

    /** @test */
    public function returns_404_for_non_existent_ticket()
    {
        // Autenticar al usuario
        Sanctum::actingAs($this->user);

        // Intentar obtener un ticket que no existe
        $response = $this->getJson('/api/tickets/999');

        // Verificar respuesta
        $response->assertStatus(404);
    }
}
