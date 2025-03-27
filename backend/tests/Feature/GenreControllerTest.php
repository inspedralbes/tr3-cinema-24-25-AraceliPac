<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Genre;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GenreControllerTest extends TestCase
{
    use DatabaseTransactions;
    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        // Buscar el rol de usuario o crearlo si no existe
        $role = Role::firstOrCreate(['name' => 'user']);

        // Crear usuario
        $this->user = User::factory()->create([
            'role_id' => $role->id
        ]);

        // Autenticar usuario
        Sanctum::actingAs($this->user);
    }

    /** @test */
    /** @test */
    public function can_get_all_genres()
    {
        // Eliminar géneros existentes que podrían interferir
        Genre::where('name', 'Comedia')->delete();
        Genre::where('name', 'Drama')->delete();

        // Crear algunos géneros
        Genre::create([
            'name' => 'Comedia',
            'description' => 'Películas de humor'
        ]);

        Genre::create([
            'name' => 'Drama',
            'description' => 'Películas con temática seria'
        ]);

        // Hacer petición
        $response = $this->getJson('/api/genres');

        // Verificar respuesta
        $response->assertStatus(200);
        $this->assertNotEmpty($response->json());
    }

    /** @test */
    public function can_get_single_genre()
    {
        // Crear género
        $genre = Genre::create([
            'name' => 'Acción',
            'description' => 'Películas con escenas de acción'
        ]);

        // Hacer petición
        $response = $this->getJson('/api/genres/' . $genre->id);

        // Verificar respuesta
        $response->assertStatus(200);
        $response->assertJson([
            'name' => 'Acción',
            'description' => 'Películas con escenas de acción'
        ]);
    }

    /** @test */
    public function can_create_genre()
    {
        // Datos del género
        $genreData = [
            'name' => 'Ciencia Ficción',
            'description' => 'Películas con temas futuristas o de ciencia'
        ];

        // Hacer petición
        $response = $this->postJson('/api/genres', $genreData);

        // Verificar respuesta
        $response->assertStatus(201);

        // Verificar en base de datos
        $this->assertDatabaseHas('genres', [
            'name' => 'Ciencia Ficción',
            'description' => 'Películas con temas futuristas o de ciencia'
        ]);
    }

    /** @test */
    /** @test */
    public function cannot_create_duplicate_genre()
    {
        // Eliminar géneros existentes que podrían interferir
        Genre::where('name', 'Terror')->delete();

        // Crear un género primero
        Genre::create([
            'name' => 'Terror',
            'description' => 'Películas de miedo'
        ]);

        // Intentar crear otro con el mismo nombre
        $duplicateData = [
            'name' => 'Terror',
            'description' => 'Otra descripción'
        ];

        // Hacer petición
        $response = $this->postJson('/api/genres', $duplicateData);

        // Verificar respuesta
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }
    /** @test */
    public function can_update_genre()
    {
        // Crear género
        $genre = Genre::create([
            'name' => 'Aventura',
            'description' => 'Descripción inicial'
        ]);

        // Datos para actualizar
        $updateData = [
            'description' => 'Películas de viajes y aventuras'
        ];

        // Hacer petición
        $response = $this->putJson('/api/genres/' . $genre->id, $updateData);

        // Verificar respuesta
        $response->assertStatus(200);

        // Verificar en base de datos
        $this->assertDatabaseHas('genres', [
            'id' => $genre->id,
            'name' => 'Aventura',
            'description' => 'Películas de viajes y aventuras'
        ]);
    }

    /** @test */
    public function can_update_genre_name()
    {
        // Crear género
        $genre = Genre::create([
            'name' => 'Documental',
            'description' => 'Películas basadas en hechos reales'
        ]);

        // Datos para actualizar
        $updateData = [
            'name' => 'Documentales'
        ];

        // Hacer petición
        $response = $this->putJson('/api/genres/' . $genre->id, $updateData);

        // Verificar respuesta
        $response->assertStatus(200);

        // Verificar en base de datos
        $this->assertDatabaseHas('genres', [
            'id' => $genre->id,
            'name' => 'Documentales'
        ]);
    }

    /** @test */
    public function cannot_update_to_existing_genre_name()
    {
        // Crear dos géneros
        Genre::create([
            'name' => 'Romance',
            'description' => 'Películas románticas'
        ]);

        $genre = Genre::create([
            'name' => 'Histórico',
            'description' => 'Películas basadas en la historia'
        ]);

        // Intentar actualizar al nombre que ya existe
        $updateData = [
            'name' => 'Romance'
        ];

        // Hacer petición
        $response = $this->putJson('/api/genres/' . $genre->id, $updateData);

        // Verificar respuesta
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    /** @test */
    public function can_delete_genre()
    {
        // Crear género
        $genre = Genre::create([
            'name' => 'Musical',
            'description' => 'Películas con música y baile'
        ]);

        // Hacer petición
        $response = $this->deleteJson('/api/genres/' . $genre->id);

        // Verificar respuesta
        $response->assertStatus(200);
        $response->assertJson(['message' => 'Gènere eliminat correctament']);

        // Verificar en base de datos
        $this->assertDatabaseMissing('genres', ['id' => $genre->id]);
    }

    /** @test */
    public function returns_404_for_non_existent_genre()
    {
        // Hacer petición con ID que no existe
        $response = $this->getJson('/api/genres/999');

        // Verificar respuesta
        $response->assertStatus(404);
        $response->assertJson(['message' => 'Gènere no trobat']);
    }
}
