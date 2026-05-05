<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('booking_users')->cascadeOnDelete();
            $table->foreignId('bilik_id')->constrained('booking_bilik')->cascadeOnDelete();
            $table->string('tajuk_mesyuarat');
            $table->date('tarikh');
            $table->time('masa_mula');
            $table->time('masa_tamat');
            $table->enum('status', ['confirmed', 'cancelled'])->default('confirmed');
            $table->string('cancel_token')->unique()->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_requests');
    }
};
