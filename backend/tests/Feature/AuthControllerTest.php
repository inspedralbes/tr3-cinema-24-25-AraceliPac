<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthControllerTest extends TestCase
{
    use   DatabaseTransactions
    , WithFaker;

    protected $role;

    public function setUp(): void
    {
        parent::setUp();

        // Crear un rol para los usuarios
        $this->role = Role::firstOrCreate(['name' => 'user']);
    }

    protected function tearDown(): void
    {
        // Limpiar todos los mocks de Mockery
        \Mockery::close();

        parent::tearDown();
    }

    /** @test */
    public function user_can_login_with_correct_credentials()
    {
        // Crear un usuario para pruebas
        $password = 'password123';
        $user = User::factory()->create([
            'password' => Hash::make($password),
            'role_id' => $this->role->id
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Login correcto'])
            ->assertJsonStructure([
                'message',
                'token',
                'user' => [
                    'id',
                    'name',
                    'last_name',
                    'email'
                ]
            ]);
    }

    /** @test */
    public function user_cannot_login_with_incorrect_password()
    {
        // Crear un usuario para pruebas
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
            'role_id' => $this->role->id
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'incorrect_password',
        ]);

        $response->assertStatus(401)
            ->assertJsonFragment(['message' => 'Credenciales incorrectas']);
    }
}
