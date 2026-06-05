<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aktivitis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_aktiviti');
            $table->date('tarikh');
            $table->string('seksyen_unit');
            $table->timestamps();
            $table->foreignId('bahagian_id')->constrained('bahagians')->onDelete('cascade');
            $table->string('biodata');
            $table->dropColumn('seksyen_unit');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aktivitis');
    }
};