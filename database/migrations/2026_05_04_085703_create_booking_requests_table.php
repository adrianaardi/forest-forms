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
            $table->date('tarikh');
            $table->time('masa_mula');
            $table->time('masa_tamat');
            $table->string('tujuan');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_requests');
    }
};
