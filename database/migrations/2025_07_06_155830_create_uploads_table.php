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
        Schema::create('uploads', function (Blueprint $table) {
            $table->id('upload_id');
            $table->string('filename');
            $table->string('title');
            $table->string('control_number');
            $table->string('type');
            $table->string('size');
            $table->string('path');
            $table->string('file_type');
            $table->integer('user_id');
            $table->integer('numdl')->default(0);
            $table->integer('status_upload')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uploads');
    }
};
