<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->updateOrInsert(
            ['name' => 'Directie'],
            [
                'is_active' => true,
                'comment' => 'Directie rol',
                'date_created' => now(),
                'date_changed' => now(),
            ]
        );
        DB::table('roles')->updateOrInsert(
            ['name' => 'Magazijnmedewerker'],
            [
                'is_active' => true,
                'comment' => 'Magazijnmedewerker rol',
                'date_created' => now(),
                'date_changed' => now(),
            ]
        );
        DB::table('roles')->updateOrInsert(
            ['name' => 'Vrijwilliger'],
            [
                'is_active' => true,
                'comment' => 'Vrijwilliger rol',
                'date_created' => now(),
                'date_changed' => now(),
            ]
        );
    }
}
