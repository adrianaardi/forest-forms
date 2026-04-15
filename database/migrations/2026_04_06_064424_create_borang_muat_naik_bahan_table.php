<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('borang_muat_naik_bahan', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('jawatan')->nullable();
            $table->string('bahagian')->nullable();
            $table->string('telefon_email')->nullable();
            $table->string('tajuk_maklumat');
            $table->text('isi_kandungan')->nullable();
            $table->string('jenis_kandungan');
            $table->string('kandungan_lain')->nullable();
            $table->string('jenis_pengemaskinian');
            $table->string('pengemaskinian_lain')->nullable();
            $table->string('fail_path')->nullable();
            $table->date('tarikh_mula')->nullable();
            $table->date('tarikh_akhir')->nullable();
            $table->enum('status', ['Pending', 'Dalam Semakan', 'Diluluskan'])->default('Pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borang_muat_naik_bahan');
    }
};