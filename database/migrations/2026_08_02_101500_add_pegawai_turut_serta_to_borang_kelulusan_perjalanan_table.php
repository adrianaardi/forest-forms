<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('borang_kelulusan_perjalanan', function (Blueprint $table) {
            if (!Schema::hasColumn('borang_kelulusan_perjalanan', 'pegawai_turut_serta')) {
                $table->json('pegawai_turut_serta')->nullable()->after('jawatan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('borang_kelulusan_perjalanan', function (Blueprint $table) {
            if (Schema::hasColumn('borang_kelulusan_perjalanan', 'pegawai_turut_serta')) {
                $table->dropColumn('pegawai_turut_serta');
            }
        });
    }
};
