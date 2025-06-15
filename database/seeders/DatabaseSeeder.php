<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Supprime complètement la création d'utilisateur SQLite
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Garde uniquement notre AdminUserSeeder
        $this->call([
            AdminUserSeeder::class
        ]);
    }
}
