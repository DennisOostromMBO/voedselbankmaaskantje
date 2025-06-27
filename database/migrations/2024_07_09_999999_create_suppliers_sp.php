<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

return new class extends Migration
{
    public function up(): void
    {
        // Drop the stored procedure if it exists
        DB::unprepared('DROP PROCEDURE IF EXISTS get_all_suppliers');

        // Load and create the stored procedure from the SQL file
        $spPath = database_path('sp/daniel/get_all_suppliers.sql');
        DB::unprepared(File::get($spPath));
    }

    public function down(): void
    {
        // Drop the stored procedure
        DB::unprepared('DROP PROCEDURE IF EXISTS get_all_suppliers');
    }
};
                 
