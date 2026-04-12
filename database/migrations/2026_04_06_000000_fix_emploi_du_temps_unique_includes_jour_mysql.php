<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Replace edt_unique_groupe_date_creneau (groupe + date + créneau only) with a key that includes jour,
     * so the same group can use S1 on Lundi and Mercredi the same week.
     *
     * MySQL may refuse DROP INDEX if that unique index is the only one InnoDB uses for groupe_id FK;
     * we add a plain index on groupe_id first.
     */
    public function up(): void
    {
        if (! Schema::hasTable('emploi_du_temps')) {
            return;
        }

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            $this->upgradeMysql();

            return;
        }

        $this->upgradeViaBlueprint();
    }

    public function down(): void
    {
        // Intentionally empty: reversing risks restoring a broken unique key on live data.
    }

    private function upgradeMysql(): void
    {
        $db = Schema::getConnection()->getDatabaseName();

        $hasOld = DB::selectOne(
            'SELECT 1 AS ok FROM information_schema.statistics WHERE table_schema = ? AND table_name = ? AND index_name = ? LIMIT 1',
            [$db, 'emploi_du_temps', 'edt_unique_groupe_date_creneau']
        );

        $hasNew = DB::selectOne(
            'SELECT 1 AS ok FROM information_schema.statistics WHERE table_schema = ? AND table_name = ? AND index_name = ? LIMIT 1',
            [$db, 'emploi_du_temps', 'edt_unique_groupe_date_jour_creneau']
        );

        if ($hasNew && ! $hasOld) {
            return;
        }

        $hasNonUniqueGroupe = DB::selectOne(
            'SELECT 1 AS ok FROM information_schema.statistics WHERE table_schema = ? AND table_name = ? AND column_name = ? AND seq_in_index = 1 AND non_unique = 1 LIMIT 1',
            [$db, 'emploi_du_temps', 'groupe_id']
        );

        if (! $hasNonUniqueGroupe) {
            Schema::table('emploi_du_temps', function (Blueprint $table) {
                $table->index('groupe_id', 'emploi_du_temps_groupe_id_index');
            });
        }

        if ($hasOld) {
            DB::statement('ALTER TABLE emploi_du_temps DROP INDEX edt_unique_groupe_date_creneau');
        }

        if (! $hasNew) {
            Schema::table('emploi_du_temps', function (Blueprint $table) {
                $table->unique(
                    ['groupe_id', 'date', 'jour', 'creneau'],
                    'edt_unique_groupe_date_jour_creneau'
                );
            });
        }
    }

    private function upgradeViaBlueprint(): void
    {
        Schema::table('emploi_du_temps', function (Blueprint $table) {
            try {
                $table->index('groupe_id', 'emploi_du_temps_groupe_id_index');
            } catch (\Throwable $e) {
                // already present
            }
        });

        Schema::table('emploi_du_temps', function (Blueprint $table) {
            try {
                $table->dropUnique('edt_unique_groupe_date_creneau');
            } catch (\Throwable $e) {
                // already dropped
            }
        });

        Schema::table('emploi_du_temps', function (Blueprint $table) {
            try {
                $table->unique(
                    ['groupe_id', 'date', 'jour', 'creneau'],
                    'edt_unique_groupe_date_jour_creneau'
                );
            } catch (\Throwable $e) {
                // already exists
            }
        });
    }
};
