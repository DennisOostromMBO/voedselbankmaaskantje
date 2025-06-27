<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

return new class extends Migration
{
    public function up(): void
    {
        // Drop bestaande procedures
        DB::unprepared('DROP PROCEDURE IF EXISTS create_stocks');
        DB::unprepared('DROP PROCEDURE IF EXISTS update_stocks');
        DB::unprepared('DROP PROCEDURE IF EXISTS destroy_stocks');
        DB::unprepared('DROP PROCEDURE IF EXISTS get_all_stocks');

        // Procedures aanmaken vanuit SQL-bestanden
        $pathCreate = database_path('sp/Mahdi/create_stocks.sql');
        DB::unprepared(File::get($pathCreate));

        $pathUpdate = database_path('sp/Mahdi/update_stocks.sql');
        DB::unprepared(File::get($pathUpdate));

        $pathDestroy = database_path('sp/Mahdi/destroy_stocks.sql');
        DB::unprepared(File::get($pathDestroy));

        $pathGetAll = database_path('sp/Mahdi/get_all_stocks.sql');
        DB::unprepared(File::get($pathGetAll));
    }

    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS create_stocks');
        DB::unprepared('DROP PROCEDURE IF EXISTS update_stocks');
        DB::unprepared('DROP PROCEDURE IF EXISTS destroy_stocks');
        DB::unprepared('DROP PROCEDURE IF EXISTS get_all_stocks');
    }
};
