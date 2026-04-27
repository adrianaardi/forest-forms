<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('borang_aduan_kerosakan', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('jawatan')->nullable();
            $table->string('bahagian')->nullable();
            $table->string('telefon')->nullable();
            $table->string('emel');
            $table->date('tarikh_aduan');
            $table->time('masa_aduan');
            $table->string('kategori_masalah');
            $table->string('masalah_lain')->nullable();
            $table->text('keterangan_kerosakan')->nullable();
            $table->enum('status', ['Belum Selesai', 'Dalam Tindakan','Tindakan Pembekal SAINS/Luar', 'Tangguh/KIV', 'Selesai'])->default('Belum Selesai');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borang_aduan_kerosakan');
    }
};