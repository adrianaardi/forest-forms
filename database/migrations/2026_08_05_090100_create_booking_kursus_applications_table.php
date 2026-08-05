<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_kursus_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kursus_id')->constrained('booking_kursuses')->cascadeOnDelete();
            $table->foreignId('booking_user_id')->constrained('booking_users')->cascadeOnDelete();
            $table->string('status')->default('pending');
            $table->timestamps();

            $table->unique(['kursus_id', 'booking_user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_kursus_applications');
    }
};