<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('booking_users', 'wilayah_id')) {
            Schema::table('booking_users', function (Blueprint $table) {
                $table->foreignId('wilayah_id')->nullable()->constrained('wilayahs')->nullOnDelete()->after('bahagian');
            });
        }

        if (!Schema::hasColumn('booking_bilik', 'wilayah_id')) {
            Schema::table('booking_bilik', function (Blueprint $table) {
                $table->foreignId('wilayah_id')->nullable()->constrained('wilayahs')->nullOnDelete()->after('wing');
            });
        }
    }

    public function down(): void
    {
        Schema::table('booking_users', function (Blueprint $table) {
            $table->dropForeign(['wilayah_id']);
            $table->dropColumn('wilayah_id');
        });
        Schema::table('booking_bilik', function (Blueprint $table) {
            $table->dropForeign(['wilayah_id']);
            $table->dropColumn('wilayah_id');
        });
    }
};
