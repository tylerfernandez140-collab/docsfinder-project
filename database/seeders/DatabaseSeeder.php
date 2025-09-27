<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Schema::disableForeignKeyConstraints();
        // User::truncate();
        // Schema::enableForeignKeyConstraints();
        $this->call(RoleSeeder::class);
        $this->call(PermissionSeeder::class);
        User::factory(10)->create();
        // Create a test user
        User::create([
            'employee_id' => 'EMP001',
            'first_name' => 'John',
            'middle_name' => 'D.',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'role_id' => 1, // Assuming 1 is a valid role ID
            'password' => Hash::make('password'),
        ]);
        User::factory()->create([
            'first_name' => 'Test',
            'middle_name' => 'User',
            'last_name' => 'Account',
            'email' => 'test@example.com',
            'role_id' => 1, // Assuming 1 is a valid role ID for superadmin
        ]);
    }
}
