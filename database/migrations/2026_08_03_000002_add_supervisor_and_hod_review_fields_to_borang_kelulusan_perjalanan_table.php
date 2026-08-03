<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('borang_kelulusan_perjalanan', function (Blueprint $table) {
            if (!Schema::hasColumn('borang_kelulusan_perjalanan', 'supervisor_user_id')) {
                $table->foreignId('supervisor_user_id')->nullable()->after('reviewed_at')->constrained('booking_users')->nullOnDelete();
            }

            if (!Schema::hasColumn('borang_kelulusan_perjalanan', 'supervisor_signature_path')) {
                $table->string('supervisor_signature_path')->nullable()->after('supervisor_user_id');
            }

            if (!Schema::hasColumn('borang_kelulusan_perjalanan', 'supervisor_status')) {
                $table->string('supervisor_status')->nullable()->after('supervisor_signature_path');
            }

            if (!Schema::hasColumn('borang_kelulusan_perjalanan', 'supervisor_reviewed_at')) {
                $table->timestamp('supervisor_reviewed_at')->nullable()->after('supervisor_status');
            }

            if (!Schema::hasColumn('borang_kelulusan_perjalanan', 'hod_user_id')) {
                $table->foreignId('hod_user_id')->nullable()->after('supervisor_reviewed_at')->constrained('booking_users')->nullOnDelete();
            }

            if (!Schema::hasColumn('borang_kelulusan_perjalanan', 'hod_signature_path')) {
                $table->string('hod_signature_path')->nullable()->after('hod_user_id');
            }

            if (!Schema::hasColumn('borang_kelulusan_perjalanan', 'hod_status')) {
                $table->string('hod_status')->nullable()->after('hod_signature_path');
            }

            if (!Schema::hasColumn('borang_kelulusan_perjalanan', 'hod_catatan')) {
                $table->text('hod_catatan')->nullable()->after('hod_status');
            }

            if (!Schema::hasColumn('borang_kelulusan_perjalanan', 'hod_reviewed_at')) {
                $table->timestamp('hod_reviewed_at')->nullable()->after('hod_catatan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('borang_kelulusan_perjalanan', function (Blueprint $table) {
            if (Schema::hasColumn('borang_kelulusan_perjalanan', 'hod_reviewed_at')) {
                $table->dropColumn('hod_reviewed_at');
            }

            if (Schema::hasColumn('borang_kelulusan_perjalanan', 'hod_catatan')) {
                $table->dropColumn('hod_catatan');
            }

            if (Schema::hasColumn('borang_kelulusan_perjalanan', 'hod_status')) {
                $table->dropColumn('hod_status');
            }

            if (Schema::hasColumn('borang_kelulusan_perjalanan', 'hod_signature_path')) {
                $table->dropColumn('hod_signature_path');
            }

            if (Schema::hasColumn('borang_kelulusan_perjalanan', 'hod_user_id')) {
                $table->dropConstrainedForeignId('hod_user_id');
            }

            if (Schema::hasColumn('borang_kelulusan_perjalanan', 'supervisor_reviewed_at')) {
                $table->dropColumn('supervisor_reviewed_at');
            }

            if (Schema::hasColumn('borang_kelulusan_perjalanan', 'supervisor_status')) {
                $table->dropColumn('supervisor_status');
            }

            if (Schema::hasColumn('borang_kelulusan_perjalanan', 'supervisor_signature_path')) {
                $table->dropColumn('supervisor_signature_path');
            }

            if (Schema::hasColumn('borang_kelulusan_perjalanan', 'supervisor_user_id')) {
                $table->dropConstrainedForeignId('supervisor_user_id');
            }
        });
    }
};