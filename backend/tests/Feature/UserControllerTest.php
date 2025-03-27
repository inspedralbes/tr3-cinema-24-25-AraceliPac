<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;
    protected $role;

    public function setUp(): void
    {
        parent::setUp();

        $this->role = Role::firstOrCreate(['name' => 'user']);

        // Crear un usuario para las pruebas
        $this->user = User::factory()->create([
            'role_id' => $this->role->id,
            'password' => bcrypt('password')
        ]);

        // Autenticar el usuario
        Sanctum::actingAs($this->user);
    }

    /** @test */
    public function can_get_all_users()
    {
        // Crear algunos usuarios adicionales
        User::factory()->count(3)->create([
            'role_id' => $this->role->id
        ]);

        // Hacer petición
        $response = $this->getJson('/api/users');

        // Comprobar respuesta
        $response->assertStatus(200);
        $this->assertEquals(User::count(), count($response->json()));
    }

    /** @test */
    public function can_create_user()
    {
        $userData = [
            'name' => 'Test Name',
            'last_name' => 'Test Last Name',
            'email' => 'test@example.com',
            'password' => 'password123',
            'role_id' => $this->role->id,
        ];

        $response = $this->postJson('/api/users', $userData);

        $response->assertStatus(201)
            ->assertJsonFragment(['name' => 'Test Name']);
    }

    /** @test */
    public function can_get_single_user()
    {
        $response = $this->getJson('/api/users/' . $this->user->id);

        $response->assertStatus(200)
            ->assertJson(['id' => $this->user->id]);
    }

    /** @test */
    public function can_update_user()
    {
        $response = $this->putJson('/api/users/' . $this->user->id, [
            'name' => 'Updated Name'
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Updated Name']);
    }

    /** @test */
    public function can_delete_user()
    {
        $userId = $this->user->id;

        $response = $this->deleteJson('/api/users/' . $userId);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('users', [
            'id' => $userId
        ]);

        $this->assertNull(User::find($userId));
    }
}
