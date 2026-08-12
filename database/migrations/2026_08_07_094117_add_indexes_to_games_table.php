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
        Schema::table('games', function (Blueprint $table) {
            $table->index('type');
            $table->index('price');
            $table->index('discount_percent');
            $table->index('release_date');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropIndex(['type']);
            $table->dropIndex(['price']);
            $table->dropIndex(['discount_percent']);
            $table->dropIndex(['release_date']);
            $table->dropIndex(['is_active']);
        });
    }
};
