<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // department name
            $table->foreignId('college_id')->constrained('colleges')->onDelete('cascade');
            $table->unsignedInteger('programs'); // no. of programs
            $table->string('head')->nullable(); // department head
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
