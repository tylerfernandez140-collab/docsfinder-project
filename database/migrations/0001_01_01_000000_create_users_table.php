<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id')->nullable();
            $table->string('name');
            $table->string('email')->unique(); // Laravel requires unique emails
            $table->timestamp('email_verified_at')->nullable();
            $table->date('dob')->nullable();
            $table->text('address')->nullable();

            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        // ⚠️ Removed password_reset_tokens since Laravel already creates it

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('users');
        // No need to drop password_reset_tokens because Laravel manages it
    }
};
