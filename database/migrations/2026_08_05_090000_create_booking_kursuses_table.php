<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_kursuses', function (Blueprint $table) {
            $table->id();
            $table->string('tajuk');
            $table->string('penganjur');
            $table->text('ringkasan');
            $table->string('lokasi');
            $table->date('tarikh_mula');
            $table->date('tarikh_tamat');
            $table->unsignedInteger('jumlah_tempat');
            $table->decimal('yuran', 10, 2)->nullable();
            $table->boolean('is_dalam_sarawak')->default(true);
            $table->foreignId('created_by')->constrained('booking_users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_kursuses');
    }
};