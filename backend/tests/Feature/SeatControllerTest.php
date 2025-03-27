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
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SeatControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;
    protected $movie;
    protected $screening;

    public function setUp(): void
    {
        parent::setUp();

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
                'screening_time' => '20:00',
            ],
            ['is_special_day' => false]
        );
    }

    /** @test */
    public function user_can_get_seats_for_screening()
    {
        // Crear algunas butacas para la sesión
        Seat::create([
            'screening_id' => $this->screening->id,
            'row' => 'A',
            'number' => 1,
            'is_vip' => false,
            'is_occupied' => false
        ]);

        Seat::create([
            'screening_id' => $this->screening->id,
            'row' => 'A',
            'number' => 2,
            'is_vip' => true,
            'is_occupied' => false
        ]);

        // Autenticar al usuario
        Sanctum::actingAs($this->user);

        // Hacer la petición
        $response = $this->getJson('/api/screenings/' . $this->screening->id . '/seats');

        // Verificar respuesta
        $response->assertStatus(200);
        $this->assertNotEmpty($response->json());
    }

    /** @test */
    public function user_can_get_specific_seat()
    {
        // Crear una butaca
        $seat = Seat::create([
            'screening_id' => $this->screening->id,
            'row' => 'B',
            'number' => 3,
            'is_vip' => true,
            'is_occupied' => false
        ]);

        // Autenticar al usuario
        Sanctum::actingAs($this->user);

        // Hacer la petición
        $response = $this->getJson('/api/seats/' . $seat->id);

        // Verificar respuesta
        $response->assertStatus(200);
        $response->assertJson([
            'row' => 'B',
            'number' => 3,
            'is_vip' => true
        ]);
    }

    /** @test */
    public function user_can_create_a_seat()
    {
        // Autenticar al usuario (asumiendo que tiene permisos)
        Sanctum::actingAs($this->user);

        // Datos para crear una butaca
        $seatData = [
            'screening_id' => $this->screening->id,
            'row' => 'C',
            'number' => 5,
            'is_vip' => true,
            'is_occupied' => false
        ];

        // Hacer la petición
        $response = $this->postJson('/api/screenings/' . $this->screening->id . '/seats', $seatData);

        // Verificar respuesta
        $response->assertStatus(201);

        // Verificar que se ha creado la butaca en la base de datos
        $this->assertDatabaseHas('seats', [
            'screening_id' => $this->screening->id,
            'row' => 'C',
            'number' => 5,
            'is_vip' => 1
        ]);
    }

    /** @test */
    public function cannot_create_duplicate_seat()
    {
        // Crear una butaca primero
        Seat::create([
            'screening_id' => $this->screening->id,
            'row' => 'D',
            'number' => 6,
            'is_vip' => false,
            'is_occupied' => false
        ]);

        // Autenticar al usuario
        Sanctum::actingAs($this->user);

        // Datos para intentar crear una butaca duplicada
        $duplicateSeatData = [
            'screening_id' => $this->screening->id,
            'row' => 'D',
            'number' => 6, // Misma fila y número
            'is_vip' => true,
            'is_occupied' => false
        ];

        // Hacer la petición
        $response = $this->postJson('/api/screenings/' . $this->screening->id . '/seats', $duplicateSeatData);

        // Verificar respuesta de error
        $response->assertStatus(400);
        $response->assertJson(['message' => 'Aquesta butaca ja existeix per a aquesta sessió']);
    }

    /** @test */
    public function user_can_update_seat_status()
    {
        // Crear una butaca
        $seat = Seat::create([
            'screening_id' => $this->screening->id,
            'row' => 'E',
            'number' => 7,
            'is_vip' => false,
            'is_occupied' => false
        ]);

        // Autenticar al usuario
        Sanctum::actingAs($this->user);

        // Datos para actualizar la butaca
        $updateData = [
            'is_occupied' => true
        ];

        // Hacer la petición
        $response = $this->putJson('/api/seats/' . $seat->id, $updateData);

        // Verificar respuesta
        $response->assertStatus(200);

        // Verificar que la butaca se actualizó
        $this->assertDatabaseHas('seats', [
            'id' => $seat->id,
            'is_occupied' => 1
        ]);
    }

    /** @test */
    public function user_can_delete_a_seat()
    {
        // Crear una butaca
        $seat = Seat::create([
            'screening_id' => $this->screening->id,
            'row' => 'F',
            'number' => 8,
            'is_vip' => false,
            'is_occupied' => false
        ]);

        // Autenticar al usuario
        Sanctum::actingAs($this->user);

        // Hacer la petición
        $response = $this->deleteJson('/api/seats/' . $seat->id);

        // Verificar respuesta
        $response->assertStatus(200);
        $response->assertJson(['message' => 'Butaca eliminada correctament']);

        // Verificar que la butaca se eliminó
        $this->assertDatabaseMissing('seats', ['id' => $seat->id]);
    }

    /** @test */
    /** @test */
    public function returns_404_for_non_existent_seat()
    {
        // Autenticar al usuario
        Sanctum::actingAs($this->user);

        // Obtener el máximo ID actual de butacas y agregar uno más para asegurar que no exista
        $maxId = Seat::max('id') ?? 0;
        $nonExistentId = $maxId + 1000; // Agrega un margen amplio para mayor seguridad

        // Intentar obtener una butaca que no existe
        $response = $this->getJson('/api/seats/' . $nonExistentId);

        // Verificar respuesta
        $response->assertStatus(404);
        $response->assertJson(['message' => 'Butaca no trobada']);
    }

    /** @test */
    public function seat_validation_fails_with_invalid_data()
    {
        // Autenticar al usuario
        Sanctum::actingAs($this->user);

        // Datos inválidos (fila fuera del rango permitido)
        $invalidData = [
            'screening_id' => $this->screening->id,
            'row' => 'Z', // Fila no válida (fuera de A-L)
            'number' => 5,
            'is_vip' => false
        ];

        // Hacer la petición
        $response = $this->postJson('/api/screenings/' . $this->screening->id . '/seats', $invalidData);

        // Verificar respuesta de error de validación
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['row']);
    }
}
