<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->count(10)->create();
        // Crear un usuario administrador
        User::create([
            'name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@mail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password1'), // Encriptar contraseña
            'phone' => '123456789',
            'role_id' => 1, // ID del rol administrador
            'image' => null,
        ]);
    }
}
