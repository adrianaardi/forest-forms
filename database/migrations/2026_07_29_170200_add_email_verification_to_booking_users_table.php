<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booking_users', function (Blueprint $table) {
            if (!Schema::hasColumn('booking_users', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable()->after('email');
            }

            if (!Schema::hasColumn('booking_users', 'email_verification_token')) {
                $table->string('email_verification_token', 128)->nullable()->after('email_verified_at');
            }
        });

        // Keep current users active: mark legacy accounts as already verified.
        \Illuminate\Support\Facades\DB::table('booking_users')
            ->whereNull('email_verified_at')
            ->update([
                'email_verified_at' => now(),
                'email_verification_token' => null,
            ]);
    }

    public function down(): void
    {
        Schema::table('booking_users', function (Blueprint $table) {
            if (Schema::hasColumn('booking_users', 'email_verification_token')) {
                $table->dropColumn('email_verification_token');
            }

            if (Schema::hasColumn('booking_users', 'email_verified_at')) {
                $table->dropColumn('email_verified_at');
            }
        });
    }
};