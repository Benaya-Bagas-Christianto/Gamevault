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
        if (Schema::hasColumn('reviews', 'image_path')) {
            Schema::table('reviews', function (Blueprint $table) {
                $table->renameColumn('image_path', 'media_path');
            });
        }
        Schema::table('reviews', function (Blueprint $table) {
            $table->string('media_type')->nullable()->after('media_path');
            $table->integer('video_start')->nullable();
            $table->integer('video_end')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->renameColumn('media_path', 'image_path');
            $table->dropColumn(['media_type', 'video_start', 'video_end']);
        });
    }
};
