<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Google\Cloud\Firestore\FirestoreClient;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            $credentials = config('firebase.credentials');
            $firestore = new FirestoreClient([
                'keyFilePath' => $credentials,
            ]);

            $usersCollection = $firestore->collection('developpeurs');

            // Vérifie si l'admin existe déjà
            $adminQuery = $usersCollection->where('email', '=', 'admin@miabe.com');
            $adminDocuments = $adminQuery->documents();

            if (iterator_count($adminDocuments) === 0) {
                // Crée l'admin s'il n'existe pas
                $usersCollection->add([
                    'nom_prenom' => 'Admin',
                    'email' => 'admin@miabe.com',
                    'mot_de_passe' => Hash::make('password123'),
                    'role' => 'admin',
                    'created_at' => new \Google\Cloud\Core\Timestamp(new \DateTime()),
                    'updated_at' => new \Google\Cloud\Core\Timestamp(new \DateTime())
                ]);

                $this->command->info('Admin user created successfully!');
            } else {
                $this->command->info('Admin user already exists!');
            }
        } catch (\Exception $e) {
            $this->command->error('Error creating admin user: ' . $e->getMessage());
            throw $e;
        }
    }
}
