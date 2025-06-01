<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; // Dodaj ovu liniju
use Illuminate\Support\Facades\Hash; // Dodaj ovu liniju

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Jedini testni korisnik za HTTP Basic Auth
        User::firstOrCreate(
            ['email' => 'testuser@example.com'], // Provjeravamo postoji li već korisnik s ovim emailom
            [
                'name' => 'Test User',
                'password' => Hash::make('password123'), // Lozinka će biti heširana
            ]
        );

        $this->command->info('Test user seeded successfully!');
    }
}