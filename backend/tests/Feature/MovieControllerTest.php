<?php

namespace Tests\Feature;

use App\Models\Movie;
use App\Models\Actor;
use App\Models\Genre;
use App\Models\Director;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MovieControllerTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    protected $user;
    protected $genre;
    protected $director;
    protected $actors = [];

    public function setUp(): void
    {
        parent::setUp();

        // Crear un rol para el usuario
        $role = Role::firstOrCreate(['name' => 'admin']);

        // Crear un usuario para autenticación (si es necesario)
        $this->user = User::factory()->create([
            'role_id' => $role->id
        ]);

        // Crear datos de prueba necesarios
        $this->genre = Genre::create(['name' => 'Action']);
        $this->director = Director::create([
            'name' => 'Christopher',
            'lastname' => 'Nolan',
            'birthdate' => '1970-07-30'
        ]);

        // Crear algunos actores para pruebas
        for ($i = 0; $i < 3; $i++) {
            $this->actors[] = Actor::create([
                'name' => $this->faker->firstName,
                'lastname' => $this->faker->lastName,
                'birthdate' => $this->faker->date
            ]);
        }
    }

    /** @test */
    public function can_get_all_movies()
    {
        // Crear algunas películas para pruebas
        Movie::create([
            'title' => 'Inception',
            'release_year' => 2010,
            'genre_id' => $this->genre->id,
            'director_id' => $this->director->id
        ]);

        Movie::create([
            'title' => 'Interstellar',
            'release_year' => 2014,
            'genre_id' => $this->genre->id,
            'director_id' => $this->director->id
        ]);

        $response = $this->getJson('/api/movies');
        
        $response->assertStatus(200);
        $this->assertNotEmpty($response->json());
    }

    /** @test */
    public function can_create_a_movie()
    {
        // Si las rutas están protegidas, autenticamos al usuario
        Sanctum::actingAs($this->user);

        $movieData = [
            'title' => 'The Dark Knight',
            'description' => 'Batman fights crime in Gotham City.',
            'release_year' => 2008,
            'rating' => 'PG-13',
            'duration' => 152,
            'genre_id' => $this->genre->id,
            'director_id' => $this->director->id,
            'actor_ids' => [$this->actors[0]->id, $this->actors[1]->id]
        ];

        $response = $this->postJson('/api/movies', $movieData);

        $response->assertStatus(201)
            ->assertJsonFragment(['title' => 'The Dark Knight']);

        // Verificar que la película se creó en la base de datos
        $this->assertDatabaseHas('movies', ['title' => 'The Dark Knight']);

        // Verificar que se asociaron los actores correctamente
        $movie = Movie::where('title', 'The Dark Knight')->first();
        $this->assertEquals(2, $movie->actors()->count());
    }

    /** @test */
    public function can_show_a_movie()
    {
        // Crear una película con actores
        $movie = Movie::create([
            'title' => 'Inception',
            'release_year' => 2010,
            'genre_id' => $this->genre->id,
            'director_id' => $this->director->id
        ]);

        // Asociar actores
        $movie->actors()->attach([$this->actors[0]->id, $this->actors[1]->id]);

        $response = $this->getJson('/api/movies/' . $movie->id);

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'Inception'])
            ->assertJsonPath('actors.0.id', $this->actors[0]->id);
    }

    /** @test */
    public function can_get_actors_of_a_movie()
    {
        // Crear una película con actores
        $movie = Movie::create([
            'title' => 'Inception',
            'release_year' => 2010,
            'genre_id' => $this->genre->id,
            'director_id' => $this->director->id
        ]);

        // Asociar actores
        $movie->actors()->attach([$this->actors[0]->id, $this->actors[1]->id]);

        $response = $this->getJson('/api/movies/' . $movie->id . '/actors');

        $response->assertStatus(200)
            ->assertJsonCount(2)
            ->assertJsonPath('0.id', $this->actors[0]->id);
    }

    /** @test */
    public function can_update_a_movie()
    {
        Sanctum::actingAs($this->user);

        // Crear una película con actores
        $movie = Movie::create([
            'title' => 'Inception',
            'release_year' => 2010,
            'genre_id' => $this->genre->id,
            'director_id' => $this->director->id
        ]);

        // Asociar actores
        $movie->actors()->attach([$this->actors[0]->id]);

        // Datos para actualizar
        $updateData = [
            'title' => 'Inception (Updated)',
            'release_year' => 2010,
            'actor_ids' => [$this->actors[1]->id, $this->actors[2]->id] // Cambiar actores
        ];

        $response = $this->putJson('/api/movies/' . $movie->id, $updateData);

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'Inception (Updated)']);

        // Verificar que se actualizó la película
        $this->assertDatabaseHas('movies', ['title' => 'Inception (Updated)']);

        // Verificar que se actualizaron los actores (sync reemplaza los existentes)
        $updatedMovie = Movie::find($movie->id);
        $this->assertEquals(2, $updatedMovie->actors()->count());
        $this->assertTrue($updatedMovie->actors->contains($this->actors[1]->id));
        $this->assertTrue($updatedMovie->actors->contains($this->actors[2]->id));
        $this->assertFalse($updatedMovie->actors->contains($this->actors[0]->id));
    }

    /** @test */
    public function can_delete_a_movie()
    {
        Sanctum::actingAs($this->user);

        // Crear una película
        $movie = Movie::create([
            'title' => 'Inception',
            'release_year' => 2010,
            'genre_id' => $this->genre->id,
            'director_id' => $this->director->id
        ]);

        $response = $this->deleteJson('/api/movies/' . $movie->id);

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Película eliminada correctamente']);

        // Verificar que la película se eliminó
        $this->assertDatabaseMissing('movies', ['id' => $movie->id]);
    }

    /** @test */
    public function returns_404_for_non_existent_movie()
    {
        // Intentar obtener una película que no existe
        $response = $this->getJson('/api/movies/999');

        $response->assertStatus(404)
            ->assertJsonFragment(['message' => 'Película no encontrada']);
    }

    /** @test */
    public function validates_required_fields_when_creating_movie()
    {
        Sanctum::actingAs($this->user);

        // Datos incompletos (falta el título que es requerido)
        $movieData = [
            'release_year' => 2008,
            'genre_id' => $this->genre->id,
            'director_id' => $this->director->id
        ];

        $response = $this->postJson('/api/movies', $movieData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }
}
