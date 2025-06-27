<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

return new class extends Migration
{
    public function up(): void
    {
        // Drop bestaande procedures
        DB::unprepared('DROP PROCEDURE IF EXISTS spGetAllCustomers');
        DB::unprepared('DROP PROCEDURE IF EXISTS spDeleteCustomer');
        DB::unprepared('DROP PROCEDURE IF EXISTS spGetCustomerById');
        DB::unprepared('DROP PROCEDURE IF EXISTS spEditCustomer');
        DB::unprepared('DROP PROCEDURE IF EXISTS spCreateCustomers');

        // Procedures aanmaken vanuit SQL-bestanden
        $pathGetAll = database_path('sp/Dennis/spGetAllCustomers.sql');
        DB::unprepared(File::get($pathGetAll));

        $pathDelete = database_path('sp/Dennis/spDeleteCustomers.sql');
        DB::unprepared(File::get($pathDelete));

        $pathGetById = database_path('sp/Dennis/spGetCustomerById.sql');
        DB::unprepared(File::get($pathGetById));

        $pathGetById = database_path('sp/Dennis/spCreateCustomers.sql');
        DB::unprepared(File::get($pathGetById));
    }

    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS spGetAllCustomers');
        DB::unprepared('DROP PROCEDURE IF EXISTS spDeleteCustomer');
        DB::unprepared('DROP PROCEDURE IF EXISTS spGetCustomerById');
        DB::unprepared('DROP PROCEDURE IF EXISTS spEditCustomer');
        DB::unprepared('DROP PROCEDURE IF EXISTS spCreateCustomer');
    }
};