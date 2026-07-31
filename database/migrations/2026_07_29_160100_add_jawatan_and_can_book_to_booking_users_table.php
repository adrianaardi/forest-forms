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
            if (!Schema::hasColumn('booking_users', 'jawatan')) {
                $table->string('jawatan')->nullable()->after('bahagian');
            }

            if (!Schema::hasColumn('booking_users', 'can_book')) {
                $table->boolean('can_book')->default(false)->after('status');
            }
        });

        // Existing users should remain able to book.
        DB::table('booking_users')->update(['can_book' => true]);
    }

    public function down(): void
    {
        Schema::table('booking_users', function (Blueprint $table) {
            if (Schema::hasColumn('booking_users', 'can_book')) {
                $table->dropColumn('can_book');
            }

            if (Schema::hasColumn('booking_users', 'jawatan')) {
                $table->dropColumn('jawatan');
            }
        });
    }
};