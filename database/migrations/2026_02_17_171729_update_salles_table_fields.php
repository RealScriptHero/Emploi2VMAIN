<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('salles', function (Blueprint $table) {
            // Drop old columns if they exist
            if (Schema::hasColumn('salles', 'ville')) {
                $table->dropColumn('ville');
            }
            if (Schema::hasColumn('salles', 'adresse')) {
                $table->dropColumn('adresse');
            }
            // Drop nomSalle if it already exists and nomCentre exists, then rename
            if (Schema::hasColumn('salles', 'nomCentre') && !Schema::hasColumn('salles', 'nomSalle')) {
                $table->renameColumn('nomCentre', 'nomSalle');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salles', function (Blueprint $table) {
            if (Schema::hasColumn('salles', 'nomSalle') && !Schema::hasColumn('salles', 'nomCentre')) {
                $table->renameColumn('nomSalle', 'nomCentre');
            }
            if (!Schema::hasColumn('salles', 'ville')) {
                $table->string('ville')->nullable();
            }
            if (!Schema::hasColumn('salles', 'adresse')) {
                $table->string('adresse')->nullable();
            }
        });
    }
};
