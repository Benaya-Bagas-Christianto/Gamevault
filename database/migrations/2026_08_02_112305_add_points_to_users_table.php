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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('points')->default(0);
            $table->unsignedInteger('spending_progress')->default(0);
            $table->foreignId('equipped_frame_id')->nullable()->constrained('point_items')->nullOnDelete();
            $table->foreignId('equipped_background_id')->nullable()->constrained('point_items')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['equipped_frame_id']);
            $table->dropForeign(['equipped_background_id']);
            $table->dropColumn(['points', 'spending_progress', 'equipped_frame_id', 'equipped_background_id']);
        });
    }
};
