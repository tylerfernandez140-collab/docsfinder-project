<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $withinTransaction = false;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('process_manuals', function (Blueprint $table) {
         $table->id();
    $table->string('title');
    $table->string('control_number')->unique();
    $table->string('type')->nullable();
    $table->string('status')->default('pending');
    $table->string('version')->nullable();
    $table->integer('revisions')->default(0);
    $table->unsignedBigInteger('owner_id')->nullable();
    $table->integer('numdl')->default(0);
    $table->string('filename');
    $table->string('path');
    $table->timestamps();

    $table->foreign('owner_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('process_manuals');
    }
};
