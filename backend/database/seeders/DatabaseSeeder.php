<?php

namespace Database\Seeders;

use App\Models\Seat;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesSeeder::class,
            UsersSeeder::class,
            GenreSeeder::class,
            DirectorSeeder::class,
            MovieSeeder::class,
            ScreeningSeeder::class,
            SeatSeeder::class,
            TicketSeeder::class,
            ActorSeeder::class,
            MovieActorSeeder::class,
        ]);
    }
}
