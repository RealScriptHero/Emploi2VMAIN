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
        Schema::table('groupes', function (Blueprint $table) {
            if (!Schema::hasColumn('groupes', 'notes')) {
                $table->text('notes')->nullable();
            }
            if (!Schema::hasColumn('groupes', 'active')) {
                $table->boolean('active')->default(true);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('groupes', function (Blueprint $table) {
            if (Schema::hasColumn('groupes', 'notes')) {
                $table->dropColumn('notes');
            }
            if (Schema::hasColumn('groupes', 'active')) {
                $table->dropColumn('active');
            }
        });
    }
};
