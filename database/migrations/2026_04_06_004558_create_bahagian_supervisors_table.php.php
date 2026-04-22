<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bahagian_supervisors', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bahagian');
            $table->string('email_supervisor');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bahagian_supervisors');
    }
};