<?php

namespace Tests\Feature;

use App\Models\Screening;
use App\Models\Movie;
use App\Models\Genre;
use App\Models\Director;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ScreeningControllerTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    protected $user;
    protected $movie;

    public function setUp(): void
    {
        parent::setUp();

        // Crear un rol para el usuario
        $role = Role::firstOrCreate(['name' => 'admin']);

        // Crear un usuario para autenticación (si es necesario)
        $this->user = User::factory()->create([
            'role_id' => $role->id
        ]);

        // Crear un género y un director para la película
        $genre = Genre::firstOrCreate(['name' => 'Drama']);

        // Buscar o crear el director
        $director = Director::firstOrCreate(
            ['name' => 'Martin', 'lastname' => 'Scorsese'],
            ['birthdate' => '1942-11-17']
        );

        // Crear una película para las sesiones
        // Verificar primero si ya existe
        $this->movie = Movie::firstOrCreate(
            ['title' => 'The Departed', 'release_year' => 2006],
            [
                'genre_id' => $genre->id,
                'director_id' => $director->id
            ]
        );
    }

    /** @test */
    /** @test */
    public function can_get_all_screenings()
    {
        // Limpiar sesiones existentes para evitar interferencias
        Screening::query()->delete();

        // Crear algunas sesiones de prueba
        Screening::create([
            'movie_id' => $this->movie->id,
            'screening_date' => '2025-04-01',
            'screening_time' => '16:00',
            'is_special_day' => false
        ]);

        Screening::create([
            'movie_id' => $this->movie->id,
            'screening_date' => '2025-04-01',
            'screening_time' => '18:00',
            'is_special_day' => true
        ]);

        $response = $this->getJson('/api/screenings');

        $response->assertStatus(200);
        $this->assertNotEmpty($response->json());

        // Verificar que las sesiones creadas estén en la respuesta
        $responseData = $response->json();

        // Buscar una sesión con el título de película esperado
        $foundScreening = collect($responseData)->first(function ($screening) {
            return $screening['movie']['title'] === 'The Departed' &&
                $screening['screening_time'] === '16:00:00';
        });

        $this->assertNotNull($foundScreening, 'No se encontró la sesión con la película "The Departed" a las 16:00');
    }

    /** @test */
    public function can_get_single_screening()
    {
        // Crear una sesión
        $screening = Screening::create([
            'movie_id' => $this->movie->id,
            'screening_date' => '2025-04-01',
            'screening_time' => '16:00',
            'is_special_day' => false
        ]);

        $response = $this->getJson('/api/screenings/' . $screening->id);

        $response->assertStatus(200);

        // Verificar solo los campos importantes, respetando su formato real
        $responseData = $response->json();
        $this->assertEquals($screening->id, $responseData['id']);
        $this->assertEquals('2025-04-01', $responseData['screening_date']);
        $this->assertEquals('16:00:00', $responseData['screening_time']);  // Nótese el formato con segundos
        $this->assertEquals(0, $responseData['is_special_day']);          // Como entero, no booleano
    }

    /** @test */
    public function returns_404_for_non_existent_screening()
    {
        $response = $this->getJson('/api/screenings/999');

        $response->assertStatus(404)
            ->assertJson(['message' => 'Sessió no trobada']);
    }

    /** @test */
    public function can_create_screening()
    {
        // Autenticar al usuario (si es necesario)
        Sanctum::actingAs($this->user);

        $screeningData = [
            'movie_id' => $this->movie->id,
            'screening_date' => '2025-04-02',
            'screening_time' => '20:00',
            'is_special_day' => true
        ];

        $response = $this->postJson('/api/screenings', $screeningData);

        $response->assertStatus(201);

        // Verificar los campos en la base de datos
        $this->assertDatabaseHas('screenings', [
            'movie_id' => $this->movie->id,
            'screening_date' => '2025-04-02',
            'is_special_day' => true
        ]);

        // Verificar que la hora se guardó correctamente (puede incluir segundos)
        $screening = Screening::latest('id')->first();
        $this->assertEquals('20:00:00', $screening->screening_time);
    }

    /** @test */
    public function cannot_create_screening_with_invalid_time()
    {
        Sanctum::actingAs($this->user);

        $screeningData = [
            'movie_id' => $this->movie->id,
            'screening_date' => '2025-04-02',
            'screening_time' => '17:00', // Hora no permitida
            'is_special_day' => false
        ];

        $response = $this->postJson('/api/screenings', $screeningData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['screening_time']);
    }

    /** @test */
    public function cannot_create_duplicate_screening()
    {
        Sanctum::actingAs($this->user);

        // Crear una sesión
        Screening::create([
            'movie_id' => $this->movie->id,
            'screening_date' => '2025-04-03',
            'screening_time' => '18:00',
            'is_special_day' => false
        ]);

        // Intentar crear otra sesión en la misma fecha y hora
        $screeningData = [
            'movie_id' => $this->movie->id,
            'screening_date' => '2025-04-03',
            'screening_time' => '18:00',
            'is_special_day' => true
        ];

        $response = $this->postJson('/api/screenings', $screeningData);

        $response->assertStatus(400)
            ->assertJson(['message' => 'Ja existeix una sessió per a aquest dia i hora']);
    }

    /** @test */
    public function can_update_screening()
    {
        Sanctum::actingAs($this->user);

        // Crear una sesión
        $screening = Screening::create([
            'movie_id' => $this->movie->id,
            'screening_date' => '2025-04-04',
            'screening_time' => '16:00',
            'is_special_day' => false
        ]);

        // Datos para actualizar
        $updateData = [
            'screening_time' => '18:00',
            'is_special_day' => true
        ];

        $response = $this->putJson('/api/screenings/' . $screening->id, $updateData);

        $response->assertStatus(200);

        // Verificar que los datos se actualizaron correctamente
        $updatedScreening = Screening::find($screening->id);
        $this->assertEquals('18:00:00', $updatedScreening->screening_time);
        $this->assertEquals(1, $updatedScreening->is_special_day);
    }

    /** @test */
    public function cannot_update_to_duplicate_screening_time()
    {
        Sanctum::actingAs($this->user);

        // Crear dos sesiones
        Screening::create([
            'movie_id' => $this->movie->id,
            'screening_date' => '2025-04-05',
            'screening_time' => '16:00',
            'is_special_day' => false
        ]);

        $screening2 = Screening::create([
            'movie_id' => $this->movie->id,
            'screening_date' => '2025-04-05',
            'screening_time' => '18:00',
            'is_special_day' => false
        ]);

        // Intentar actualizar la segunda sesión a la misma hora que la primera
        $updateData = [
            'screening_date' => '2025-04-05', // Necesario incluir la misma fecha
            'screening_time' => '16:00'
        ];

        $response = $this->putJson('/api/screenings/' . $screening2->id, $updateData);

        $response->assertStatus(400)
            ->assertJson(['message' => 'Ja existeix una sessió per a aquest dia i hora']);
    }

    /** @test */
    public function can_delete_screening()
    {
        Sanctum::actingAs($this->user);

        // Crear una sesión
        $screening = Screening::create([
            'movie_id' => $this->movie->id,
            'screening_date' => '2025-04-06',
            'screening_time' => '16:00',
            'is_special_day' => false
        ]);

        $response = $this->deleteJson('/api/screenings/' . $screening->id);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Sessió eliminada correctament']);

        $this->assertDatabaseMissing('screenings', ['id' => $screening->id]);
    }
}
