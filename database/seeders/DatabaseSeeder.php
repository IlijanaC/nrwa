<?php

namespace Database\Seeders;

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
        // Pozovite novi seeder za uloge i dozvole
        $this->call(RolesAndPermissionsSeeder::class);

        // Stari test korisnik ako ga želite zadržati, ali za testiranje novih rola koristite one iz RolesAndPermissionsSeeder
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}