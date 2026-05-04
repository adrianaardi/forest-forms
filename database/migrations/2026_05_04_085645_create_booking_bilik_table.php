<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_bilik', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bilik');
            $table->string('aras');
            $table->string('lokasi')->nullable();
            $table->integer('kapasiti')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_bilik');
    }
};
