<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Utilisateur;

class AdminUserSeeder extends Seeder
{
    /**
     * Seed the default admin user.
     */
    public function run(): void
    {
        // Use updateOrCreate to avoid duplicates
        // If a user with this email exists, update it; otherwise, create it
        Utilisateur::updateOrCreate(
            ['email' => 'zharimaha@gmail.com'],  // Search condition
            [                                     // Data to create/update
                'nom' => 'Admin',
                'prenom' => 'User',
                'email' => 'zharimaha@gmail.com',
                'motDePasse' => 'ofppt1122',    // Will be auto-hashed by the model
                'role' => 'admin',
                'dateCreation' => now(),
            ]
        );
    }
}
