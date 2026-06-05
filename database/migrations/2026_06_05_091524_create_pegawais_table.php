<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pegawais', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('gred');
            $table->string('seksyen_unit');
            $table->boolean('is_hadir')->default(true); // true = Hadir, false = Tidak Hadir
            $table->timestamps();
            $table->foreignId('bahagian_id')->constrained('bahagians')->onDelete('cascade');
            $table->string('biodata'); // Serves as Seksyen/Unit string descriptor
            $table->dropColumn('seksyen_unit'); // Dropping old design field if present
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pegawais');
    }
};