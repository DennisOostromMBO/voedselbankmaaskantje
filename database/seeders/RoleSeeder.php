<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->insert([
            [
                'user_id' => 1,
                'name' => 'admin',
                'is_active' => true,
                'comment' => 'Administrator role',
                'date_created' => now(),
                'date_changed' => now(),
            ],
            [
                'user_id' => 1,
                'name' => 'user',
                'is_active' => true,
                'comment' => 'Standard user role',
                'date_created' => now(),
                'date_changed' => now(),
            ],
            [
                'user_id' => 2,
                'name' => 'manager',
                'is_active' => true,
                'comment' => null,
                'date_created' => now(),
                'date_changed' => now(),
            ],
        ]);
    }
}
