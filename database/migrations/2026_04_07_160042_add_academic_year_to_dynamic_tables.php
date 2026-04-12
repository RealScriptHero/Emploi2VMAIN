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
        // Add academic_year to emploi_du_temps table
        if (!Schema::hasColumn('emploi_du_temps', 'academic_year')) {
            Schema::table('emploi_du_temps', function (Blueprint $table) {
                $table->string('academic_year')->nullable()->after('updated_at');
                $table->index(['academic_year', 'date']);
            });
        }

        // Add academic_year to absence_formateurs table
        if (!Schema::hasColumn('absence_formateurs', 'academic_year')) {
            Schema::table('absence_formateurs', function (Blueprint $table) {
                $table->string('academic_year')->nullable()->after('updated_at');
                $table->index(['academic_year', 'dateAbsence']);
            });
        }

        // Add academic_year to absence_groupes table
        if (!Schema::hasColumn('absence_groupes', 'academic_year')) {
            Schema::table('absence_groupes', function (Blueprint $table) {
                $table->string('academic_year')->nullable()->after('updated_at');
                $table->index(['academic_year', 'dateAbsence']);
            });
        }

        // Add academic_year to avancements table
        if (!Schema::hasColumn('avancements', 'academic_year')) {
            Schema::table('avancements', function (Blueprint $table) {
                $table->string('academic_year')->nullable()->after('updated_at');
                $table->index(['academic_year', 'dateLastUpdate']);
            });
        }

        // Add academic_year to stages table
        if (!Schema::hasColumn('stages', 'academic_year')) {
            Schema::table('stages', function (Blueprint $table) {
                $table->string('academic_year')->nullable()->after('updated_at');
                $table->index(['academic_year']);
            });
        }

        // Add academic_year to groupes table for advancement tracking
        if (!Schema::hasColumn('groupes', 'academic_year')) {
            Schema::table('groupes', function (Blueprint $table) {
                $table->string('academic_year')->nullable()->after('updated_at');
                $table->index(['academic_year']);
            });
        }

        // Add academic_year to modules table for advancement tracking
        if (!Schema::hasColumn('modules', 'academic_year')) {
            Schema::table('modules', function (Blueprint $table) {
                $table->string('academic_year')->nullable()->after('updated_at');
                $table->index(['academic_year']);
            });
        }

        // Add academic_year to formateurs table for advancement tracking
        if (!Schema::hasColumn('formateurs', 'academic_year')) {
            Schema::table('formateurs', function (Blueprint $table) {
                $table->string('academic_year')->nullable()->after('updated_at');
                $table->index(['academic_year']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove academic_year from all tables
        $tables = [
            'emploi_du_temps',
            'absence_formateurs',
            'absence_groupes',
            'avancements',
            'stages',
            'groupes',
            'modules',
            'formateurs'
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropIndex(['academic_year']);
                $table->dropColumn('academic_year');
            });
        }
    }
};
