<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->cascadeOnDelete();
            $table->foreignId('game_id')->constrained()->cascadeOnDelete();
            $table->decimal('price', 12, 2); // snapshot harga saat beli
            $table->string('license_key')->nullable()->unique();
            $table->enum('refund_status', ['none', 'requested', 'approved', 'rejected'])->default('none');
            $table->timestamp('refund_requested_at')->nullable();
            $table->timestamp('refund_processed_at')->nullable();
            $table->text('refund_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_details');
    }
};
