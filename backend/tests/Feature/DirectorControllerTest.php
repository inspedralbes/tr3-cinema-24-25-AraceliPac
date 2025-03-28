<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Director;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DirectorControllerTest extends TestCase
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
    public function can_get_all_directors()
    {
        // Crear algunos directores
        Director::create([
            'name' => 'Steven',
            'lastname' => 'Spielberg',
            'birth_date' => '1946-12-18'
        ]);

        Director::create([
            'name' => 'Christopher',
            'lastname' => 'Nolan',
            'birth_date' => '1970-07-30'
        ]);

        // Hacer petición
        $response = $this->getJson('/api/directors');

        // Verificar respuesta
        $response->assertStatus(200);
        $this->assertNotEmpty($response->json());
    }

    /** @test */
    public function can_get_single_director()
    {
        // Crear director
        $director = Director::create([
            'name' => 'Martin',
            'lastname' => 'Scorsese',
            'birth_date' => '1942-11-17'
        ]);

        // Hacer petición
        $response = $this->getJson('/api/directors/' . $director->id);

        // Verificar respuesta
        $response->assertStatus(200);
        $response->assertJson([
            'name' => 'Martin',
            'lastname' => 'Scorsese'
        ]);
    }

    /** @test */
    public function can_create_director()
    {
        // Datos del director
        $directorData = [
            'name' => 'Pedro',
            'lastname' => 'Almodóvar',
            'birth_date' => '1949-09-25',
            'nationality' => 'Spanish'
        ];

        // Hacer petición
        $response = $this->postJson('/api/directors', $directorData);

        // Verificar respuesta
        $response->assertStatus(201);

        // Verificar en base de datos
        $this->assertDatabaseHas('directors', [
            'name' => 'Pedro',
            'lastname' => 'Almodóvar'
        ]);
    }

    /** @test */
    public function can_update_director()
    {
        // Crear director
        $director = Director::create([
            'name' => 'Quentin',
            'lastname' => 'Tarantino',
            'birth_date' => '1963-03-27'
        ]);

        // Datos para actualizar
        $updateData = [
            'nationality' => 'American',
            'bio' => 'Director, guionista y actor de cine estadounidense'
        ];

        // Hacer petición
        $response = $this->putJson('/api/directors/' . $director->id, $updateData);

        // Verificar respuesta
        $response->assertStatus(200);

        // Verificar en base de datos
        $this->assertDatabaseHas('directors', [
            'id' => $director->id,
            'nationality' => 'American',
            'bio' => 'Director, guionista y actor de cine estadounidense'
        ]);
    }

    /** @test */
    public function can_delete_director()
    {
        // Crear director
        $director = Director::create([
            'name' => 'Sofia',
            'lastname' => 'Coppola',
            'birth_date' => '1971-05-14'
        ]);

        // Hacer petición
        $response = $this->deleteJson('/api/directors/' . $director->id);

        // Verificar respuesta
        $response->assertStatus(200);
        $response->assertJson(['message' => 'Director eliminat correctament']);

        // Verificar en base de datos
        $this->assertDatabaseMissing('directors', ['id' => $director->id]);
    }

    /** @test */
    public function returns_404_for_non_existent_director()
    {
        // Hacer petición con ID que no existe
        $response = $this->getJson('/api/directors/999');

        // Verificar respuesta
        $response->assertStatus(404);
        $response->assertJson(['message' => 'Director no trobat']);
    }
}
