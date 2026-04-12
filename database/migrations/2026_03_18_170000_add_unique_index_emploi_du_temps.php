<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('emploi_du_temps', function (Blueprint $table) {
            // Prevent duplicates for the same group + exact date + time slot.
            // (date already identifies the calendar day; we keep jour too for readability)
            $table->unique(['groupe_id', 'date', 'creneau'], 'edt_unique_groupe_date_creneau');
        });
    }

    public function down(): void
    {
        Schema::table('emploi_du_temps', function (Blueprint $table) {
            $table->dropUnique('edt_unique_groupe_date_creneau');
        });
    }
};

