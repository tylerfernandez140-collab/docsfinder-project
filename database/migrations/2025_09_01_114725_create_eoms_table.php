<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eoms', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('control_number')->unique();
            $table->string('type')->nullable();
            $table->string('status')->default('pending'); // controlled, pending, expired
            $table->string('version')->nullable();
            $table->integer('revisions')->default(0);
            $table->unsignedBigInteger('owner_id')->nullable();
            $table->integer('numdl')->default(0); // number of downloads
            $table->string('filename');
            $table->string('path');
            $table->timestamps();

            $table->foreign('owner_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eoms');
    }
};
