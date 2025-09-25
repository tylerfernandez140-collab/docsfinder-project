<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Seed initial roles
        DB::table('roles')->insert([
            ['name' => 'super-admin', 'description' => 'Overall access and control over the other users.'],
            ['name' => 'admin', 'description' => 'Can upload documents, nothing else.'],
            ['name' => 'campus-dcc', 'description' => 'Distributes documents uploaded by the Admin to the Process Owners.'],
            ['name' => 'process-owner', 'description' => 'Can only view documents and add comments/feedback.'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
