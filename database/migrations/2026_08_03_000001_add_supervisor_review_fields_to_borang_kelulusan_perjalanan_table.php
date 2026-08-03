<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('borang_kelulusan_perjalanan', function (Blueprint $table) {
            if (!Schema::hasColumn('borang_kelulusan_perjalanan', 'booking_user_id')) {
                $table->foreignId('booking_user_id')->nullable()->after('id')->constrained('booking_users')->nullOnDelete();
            }

            if (!Schema::hasColumn('borang_kelulusan_perjalanan', 'signature_path')) {
                $table->string('signature_path')->nullable()->after('attachments');
            }

            if (!Schema::hasColumn('borang_kelulusan_perjalanan', 'status')) {
                $table->string('status')->default('Pending')->after('signature_path');
            }

            if (!Schema::hasColumn('borang_kelulusan_perjalanan', 'reviewed_at')) {
                $table->timestamp('reviewed_at')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('borang_kelulusan_perjalanan', function (Blueprint $table) {
            if (Schema::hasColumn('borang_kelulusan_perjalanan', 'reviewed_at')) {
                $table->dropColumn('reviewed_at');
            }

            if (Schema::hasColumn('borang_kelulusan_perjalanan', 'status')) {
                $table->dropColumn('status');
            }

            if (Schema::hasColumn('borang_kelulusan_perjalanan', 'signature_path')) {
                $table->dropColumn('signature_path');
            }

            if (Schema::hasColumn('borang_kelulusan_perjalanan', 'booking_user_id')) {
                $table->dropConstrainedForeignId('booking_user_id');
            }
        });
    }
};