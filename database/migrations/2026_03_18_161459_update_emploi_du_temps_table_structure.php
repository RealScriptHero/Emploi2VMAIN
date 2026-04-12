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
        Schema::table('emploi_du_temps', function (Blueprint $table) {
            // Only add columns if they don't already exist
            if (!Schema::hasColumn('emploi_du_temps', 'groupe_id')) {
                $table->foreignId('groupe_id')->nullable()->constrained('groupes')->onDelete('cascade');
            }
            if (!Schema::hasColumn('emploi_du_temps', 'creneau')) {
                $table->string('creneau')->nullable();
            }
            if (!Schema::hasColumn('emploi_du_temps', 'date_debut')) {
                $table->date('date_debut')->nullable();
            }
            if (!Schema::hasColumn('emploi_du_temps', 'date_fin')) {
                $table->date('date_fin')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('emploi_du_temps', function (Blueprint $table) {
            // Drop new columns
            $table->dropForeign(['groupe_id']);
            $table->dropColumn(['groupe_id', 'creneau', 'date_debut', 'date_fin']);
            
            // Add back old columns
            $table->string('pour');
            $table->time('heureDebut');
            $table->time('heureFin');
        });
    }
};
