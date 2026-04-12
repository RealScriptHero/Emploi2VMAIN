<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ensure table exists (do not drop it, because it is referenced by foreign keys)
        if (! Schema::hasTable('utilisateurs')) {
            Schema::create('utilisateurs', function (Blueprint $table) {
                $table->id();
                $table->string('nom');
                $table->string('prenom');
                $table->string('email')->unique();
                $table->string('motDePasse');
                $table->string('role');
                $table->timestamp('dateCreation')->useCurrent();
                $table->timestamps();
            });
        }

        // Seed or update the initial user for login (password stored as valid bcrypt hash)
        DB::table('utilisateurs')->updateOrInsert(
            ['email' => 'anasettai4@gmail.com'],
            [
                'nom'         => 'Ana',
                'prenom'      => 'Settai',
                'motDePasse'  => Hash::make('password123'),
                'role'        => 'admin',
                'dateCreation'=> now(),
                'updated_at'  => now(),
                'created_at'  => now(),
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('utilisateurs');
    }
};

