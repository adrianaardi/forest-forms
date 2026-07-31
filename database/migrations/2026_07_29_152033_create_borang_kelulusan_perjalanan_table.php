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
        Schema::create('borang_kelulusan_perjalanan', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('jawatan');
            $table->string('bahagian');
            $table->string('telefon');
            $table->string('emel');
            $table->date('tarikh_perjalanan');
            $table->string('destinasi_perjalanan');
            $table->string('jenis_kenderaan');
            $table->json('attachments')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borang_kelulusan_perjalanan');
    }
};
