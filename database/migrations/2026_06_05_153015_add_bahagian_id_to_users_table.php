<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            // Nullable for Super Admins who manage ALL divisions
            $table->foreignId('bahagian_id')->nullable()->constrained('bahagians')->onDelete('set null');
        });
    }

    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['bahagian_id']);
            $table->dropColumn('bahagian_id');
        });
    }
};