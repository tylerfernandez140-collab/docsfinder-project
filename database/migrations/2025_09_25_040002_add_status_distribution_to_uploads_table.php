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
        Schema::table('uploads', function (Blueprint $table) {
            $table->integer('status_distribution')->default(0)->after('status_upload');
            $table->string('distributed_to_designation')->nullable()->after('status_distribution');
            $table->json('distributed_to_process_owner')->nullable()->after('distributed_to_designation');
            $table->integer('distributed_by_user_id')->nullable()->after('distributed_to_process_owner');
            $table->timestamp('distributed_at')->nullable()->after('distributed_by_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('uploads', function (Blueprint $table) {
            $table->dropColumn('status_distribution');
            $table->dropColumn('distributed_to_designation');
            $table->dropColumn('distributed_to_process_owner');
            $table->dropColumn('distributed_by_user_id');
            $table->dropColumn('distributed_at');
        });
    }
};
