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
        Schema::table('aktivitis', function (Blueprint $table) {
            $table->foreignId('bahagian_id')->nullable()->constrained('bahagians')->onDelete('cascade');
            if (!Schema::hasColumn('aktivitis', 'biodata')) {
                $table->string('biodata')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aktivitis', function (Blueprint $table) {
            //
        });
    }
};
