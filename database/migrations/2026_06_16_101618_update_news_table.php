<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
    {
        Schema::table('news', function (Blueprint $table) {
            $table->unsignedBigInteger('bahagian_id')->nullable()->after('headline');
            $table->foreign('bahagian_id')->references('id')->on('bahagians')->nullOnDelete();
            $table->dropColumn('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
