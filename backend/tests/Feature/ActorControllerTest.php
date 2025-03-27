<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Actor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ActorControllerTest extends TestCase
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
    public function can_get_all_actors()
    {
        // Crear algunos actores
        Actor::create([
            'name' => 'Tom',
            'lastname' => 'Hanks',
            'birth_date' => '1956-07-09'
        ]);

        Actor::create([
            'name' => 'Meryl',
            'lastname' => 'Streep',
            'birth_date' => '1949-06-22'
        ]);

        // Hacer petición
        $response = $this->getJson('/api/actors');

        // Verificar respuesta
        $response->assertStatus(200);
        $this->assertNotEmpty($response->json());
    }

    /** @test */
    public function can_get_single_actor()
    {
        // Crear actor
        $actor = Actor::create([
            'name' => 'Leonardo',
            'lastname' => 'DiCaprio',
            'birth_date' => '1974-11-11'
        ]);

        // Hacer petición
        $response = $this->getJson('/api/actors/' . $actor->id);

        // Verificar respuesta
        $response->assertStatus(200);
        $response->assertJson([
            'name' => 'Leonardo',
            'lastname' => 'DiCaprio'
        ]);
    }

    /** @test */
    public function can_create_actor()
    {
        // Datos del actor
        $actorData = [
            'name' => 'Jennifer',
            'lastname' => 'Lawrence',
            'birth_date' => '1990-08-15',
            'nationality' => 'American'
        ];

        // Hacer petición
        $response = $this->postJson('/api/actors', $actorData);

        // Verificar respuesta
        $response->assertStatus(201);

        // Verificar en base de datos
        $this->assertDatabaseHas('actors', [
            'name' => 'Jennifer',
            'lastname' => 'Lawrence'
        ]);
    }

    /** @test */
    public function can_update_actor()
    {
        // Crear actor
        $actor = Actor::create([
            'name' => 'Brad',
            'lastname' => 'Pitt',
            'birth_date' => '1963-12-18'
        ]);

        // Datos para actualizar
        $updateData = [
            'nationality' => 'American',
            'bio' => 'Actor y productor famoso'
        ];

        // Hacer petición
        $response = $this->putJson('/api/actors/' . $actor->id, $updateData);

        // Verificar respuesta
        $response->assertStatus(200);

        // Verificar en base de datos
        $this->assertDatabaseHas('actors', [
            'id' => $actor->id,
            'nationality' => 'American',
            'bio' => 'Actor y productor famoso'
        ]);
    }

    /** @test */
    public function can_delete_actor()
    {
        // Crear actor
        $actor = Actor::create([
            'name' => 'Emma',
            'lastname' => 'Stone',
            'birth_date' => '1988-11-06'
        ]);

        // Hacer petición
        $response = $this->deleteJson('/api/actors/' . $actor->id);

        // Verificar respuesta
        $response->assertStatus(200);
        $response->assertJson(['message' => 'Actor eliminat correctament']);

        // Verificar en base de datos
        $this->assertDatabaseMissing('actors', ['id' => $actor->id]);
    }

    /** @test */
    public function returns_404_for_non_existent_actor()
    {
        // Hacer petición con ID que no existe
        $response = $this->getJson('/api/actors/999');

        // Verificar respuesta
        $response->assertStatus(404);
        $response->assertJson(['message' => 'Actor no trobat']);
    }
}
