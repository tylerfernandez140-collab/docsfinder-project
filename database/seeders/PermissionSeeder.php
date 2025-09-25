<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;
use Illuminate\Support\Facades\Schema;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            ['name' => 'upload_documents', 'display_name' => 'Upload Documents', 'description' => 'Allow user to upload documents'],
            ['name' => 'delete_documents', 'display_name' => 'Delete Documents', 'description' => 'Allow user to delete documents'],
            ['name' => 'approve_documents', 'display_name' => 'Approve Documents', 'description' => 'Allow user to approve documents'],
            ['name' => 'manage_users', 'display_name' => 'Manage Users', 'description' => 'Allow user to manage other users'],
        ];

        Schema::disableForeignKeyConstraints();
        Permission::truncate();
        Schema::enableForeignKeyConstraints();

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}
