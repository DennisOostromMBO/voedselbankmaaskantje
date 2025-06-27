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

        // Procedures aanmaken vanuit SQL-bestanden
        $pathGetAll = database_path('sp/Dennis/spGetAllCustomers.sql');
        DB::unprepared(File::get($pathGetAll));

        $pathDelete = database_path('sp/Dennis/spDeleteCustomers.sql');
        DB::unprepared(File::get($pathDelete));

        $pathGetById = database_path('sp/Dennis/spGetCustomerById.sql');
        DB::unprepared(File::get($pathGetById));

        // Zet hier direct de spEditCustomer SP zonder DELIMITER
        DB::unprepared(<<<'SQL'
DROP PROCEDURE IF EXISTS spEditCustomer;
CREATE PROCEDURE spEditCustomer(
    IN p_id INT,
    IN p_first_name VARCHAR(255),
    IN p_infix VARCHAR(50),
    IN p_last_name VARCHAR(255),
    IN p_family_name VARCHAR(255),
    IN p_full_address VARCHAR(255),
    IN p_mobile VARCHAR(255),
    IN p_email VARCHAR(255),
    IN p_age INT,
    IN p_wish VARCHAR(255)
)
BEGIN
    DECLARE v_person_id INT;
    DECLARE v_family_id INT;

    -- Haal family_id en person_id op
    SELECT family_id INTO v_family_id FROM customers WHERE id = p_id LIMIT 1;
    SELECT person_id INTO v_person_id FROM families WHERE id = v_family_id LIMIT 1;

    -- Update persons
    UPDATE persons
    SET persons.first_name = p_first_name,
        persons.infix = p_infix,
        persons.last_name = p_last_name,
        persons.age = p_age
    WHERE persons.id = v_person_id;

    -- Update families
    UPDATE families
    SET families.name = p_family_name
    WHERE families.id = v_family_id;

    -- Update contacts
    UPDATE contacts
    SET full_address = p_full_address,
        mobile = p_mobile,
        email = p_email
    WHERE customer_id = p_id;

    -- Update wishes
    UPDATE wishes
    SET choices = p_wish
    WHERE customer_id = p_id;
END;
SQL
        );
    }

    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS spGetAllCustomers');
        DB::unprepared('DROP PROCEDURE IF EXISTS spDeleteCustomer');
        DB::unprepared('DROP PROCEDURE IF EXISTS spGetCustomerById');
        DB::unprepared('DROP PROCEDURE IF EXISTS spEditCustomer');
    }
};
