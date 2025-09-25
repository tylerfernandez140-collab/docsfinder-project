<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // program name
            $table->foreignId('department_id')->constrained('departments')->onDelete('cascade');
            $table->string('level'); // e.g. undergraduate, graduate
            $table->string('accreditation')->nullable();
            $table->string('coordinator')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
