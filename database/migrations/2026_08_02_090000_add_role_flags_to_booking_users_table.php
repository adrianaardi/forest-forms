<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booking_users', function (Blueprint $table) {
            if (!Schema::hasColumn('booking_users', 'is_supervisor')) {
                $table->boolean('is_supervisor')->default(false)->after('can_book');
            }

            if (!Schema::hasColumn('booking_users', 'is_hod')) {
                $table->boolean('is_hod')->default(false)->after('is_supervisor');
            }

            if (!Schema::hasColumn('booking_users', 'is_accountant')) {
                $table->boolean('is_accountant')->default(false)->after('is_hod');
            }
        });

        DB::table('booking_users')->update([
            'is_supervisor' => false,
            'is_hod' => false,
            'is_accountant' => false,
        ]);
    }

    public function down(): void
    {
        Schema::table('booking_users', function (Blueprint $table) {
            if (Schema::hasColumn('booking_users', 'is_accountant')) {
                $table->dropColumn('is_accountant');
            }

            if (Schema::hasColumn('booking_users', 'is_hod')) {
                $table->dropColumn('is_hod');
            }

            if (Schema::hasColumn('booking_users', 'is_supervisor')) {
                $table->dropColumn('is_supervisor');
            }
        });
    }
};
