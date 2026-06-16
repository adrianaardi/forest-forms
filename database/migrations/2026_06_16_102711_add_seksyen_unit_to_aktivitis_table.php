<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('aktivitis', function (Blueprint $table) {
            $table->string('seksyen_unit')->nullable()->after('tarikh');
        });
    }

    public function down()
    {
        Schema::table('aktivitis', function (Blueprint $table) {
            $table->dropColumn('seksyen_unit');
        });
    }
};
