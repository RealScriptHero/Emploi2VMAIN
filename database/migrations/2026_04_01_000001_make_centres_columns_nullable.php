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
        Schema::table('centres', function (Blueprint $table) {
            $table->string('ville')->nullable()->change();
            $table->string('adresse')->nullable()->change();
            if (!Schema::hasColumn('centres', 'shortName')) {
                $table->string('shortName')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('centres', function (Blueprint $table) {
            $table->string('ville')->nullable(false)->change();
            $table->string('adresse')->nullable(false)->change();
        });
    }
};
