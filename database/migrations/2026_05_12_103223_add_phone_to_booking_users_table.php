<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('booking_users', 'phone')) {
            Schema::table('booking_users', function (Blueprint $table) {
                $table->string('phone')->nullable()->after('bahagian');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_users', function (Blueprint $table) {
            //
        });
    }
};
