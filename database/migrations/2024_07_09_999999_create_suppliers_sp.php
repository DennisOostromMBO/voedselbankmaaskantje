<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

return new class extends Migration
{
    public function up(): void
    {
        // Drop the stored procedures if they exist
        DB::unprepared('DROP PROCEDURE IF EXISTS get_all_suppliers');
        DB::unprepared('DROP PROCEDURE IF EXISTS create_supplier');

        // Load and create the stored procedures from the SQL files
        $getAllPath = database_path('sp/daniel/get_all_suppliers.sql');
        DB::unprepared(File::get($getAllPath));

        $createPath = database_path('sp/daniel/create_supplier.sql');
        DB::unprepared(File::get($createPath));
    }

    public function down(): void
    {
        // Drop the stored procedures
        DB::unprepared('DROP PROCEDURE IF EXISTS get_all_suppliers');
        DB::unprepared('DROP PROCEDURE IF EXISTS create_supplier');
    }
};

