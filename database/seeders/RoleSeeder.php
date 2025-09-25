<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Role::truncate();
        Schema::enableForeignKeyConstraints();
        DB::table('roles')->insert([
            ['name' => 'super-admin'],
            ['name' => 'admin'],
            ['name' => 'campus-dcc'],
            ['name' => 'process-owner'],
            ['name' => 'user'],
        ]);
    }
}
