<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
	// 1. Pfad zur SQL-Datei definieren (relativ zum 'database'-Ordner)
        $path = database_path('/sql/db_struktur.sql');

        // 2. Pruefen, ob die Datei an dem Ort wirklich existiert
	if (File::exists($path)) {
            // 3. Inhalt einlesen
            $sql = File::get($path);

            // 4. SQL-Befehle ausführen
            DB::unprepared($sql);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
