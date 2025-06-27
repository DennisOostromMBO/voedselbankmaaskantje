<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

return new class extends Migration
{
    public function up(): void
    {
        // Drop bestaande procedure
        DB::unprepared('DROP PROCEDURE IF EXISTS spGetAllCustomers');

        // Procedure aanmaken vanuit SQL-bestand
        $path = database_path('sp/Dennis/spGetAllCustomers.sql');
        DB::unprepared(File::get($path));
    }

    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS spGetAllCustomers');
    }
};
