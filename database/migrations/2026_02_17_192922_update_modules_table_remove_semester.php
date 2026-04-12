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
        Schema::table('modules', function (Blueprint $table) {
            // Drop semester column if it exists
            if (Schema::hasColumn('modules', 'semestre')) {
                $table->dropColumn('semestre');
            }
            // Add advancement column if it doesn't exist
            if (!Schema::hasColumn('modules', 'advancement')) {
                $table->integer('advancement')->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('modules', function (Blueprint $table) {
            if (!Schema::hasColumn('modules', 'semestre')) {
                $table->string('semestre')->nullable();
            }
            if (Schema::hasColumn('modules', 'advancement')) {
                $table->dropColumn('advancement');
            }
        });
    }
};
