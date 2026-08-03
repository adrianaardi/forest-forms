<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booking_users', function (Blueprint $table) {
            if (!Schema::hasColumn('booking_users', 'supervisor_id')) {
                $table->foreignId('supervisor_id')
                    ->nullable()
                    ->after('wilayah_id')
                    ->constrained('booking_users')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('booking_users', function (Blueprint $table) {
            if (Schema::hasColumn('booking_users', 'supervisor_id')) {
                $table->dropConstrainedForeignId('supervisor_id');
            }
        });
    }
};
