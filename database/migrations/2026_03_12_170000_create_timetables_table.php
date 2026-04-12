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
        Schema::create('timetables', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->unsignedBigInteger('centre_id')->nullable();
            $table->text('data')->nullable();
            $table->timestamps();

            // ensure only one timetable row exists per date (centre_id stored optionally)
            $table->unique('date');
            $table->foreign('centre_id')->references('id')->on('centres')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timetables');
    }
};
