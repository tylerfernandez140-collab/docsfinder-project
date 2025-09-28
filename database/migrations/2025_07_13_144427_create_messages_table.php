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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            
            // Foreign keys 
            $table->foreignId('group_id')->constrained()->onDelete('cascade'); 
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            $table->foreignId('parent_id')->nullable()->constrained('messages')->onDelete('cascade'); 
            
            // Message content 
            $table->text('content')->nullable(); 
            $table->string('type')->default('text'); // 'text' or 'file' 
            $table->string('file_path')->nullable(); 
            $table->string('mime_type')->nullable(); 

            // Timestamps 
            $table->timestamps(); 

            // Indexes for performance 
            $table->index(['group_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
