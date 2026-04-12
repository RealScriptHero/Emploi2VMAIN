<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Skip this migration if the table already exists (created by later migration)
        if (Schema::hasTable('emploi_du_temps')) {
            return;
        }

        Schema::create('emploi_du_temps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('groupe_id')->nullable();
            $table->unsignedBigInteger('formateur_id')->nullable();
            $table->unsignedBigInteger('module_id')->nullable();
            $table->unsignedBigInteger('salle_id')->nullable();
            $table->string('jour')->nullable();
            $table->string('creneau')->nullable();
            $table->date('date')->nullable();
            $table->timestamps();

            $table->foreign('groupe_id')->references('id')->on('groupes')->onDelete('cascade');
            $table->foreign('formateur_id')->references('id')->on('formateurs')->onDelete('cascade');
            $table->foreign('module_id')->references('id')->on('modules')->onDelete('set null');
            $table->foreign('salle_id')->references('id')->on('salles')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emploi_du_temps');
    }
};