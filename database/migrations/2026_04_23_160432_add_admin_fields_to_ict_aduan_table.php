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
        Schema::table('borang_aduan_kerosakan', function (Blueprint $table) {

            if (!Schema::hasColumn('borang_aduan_kerosakan', 'remarks')) {
                $table->text('remarks')->nullable();
            }

            if (!Schema::hasColumn('borang_aduan_kerosakan', 'nama_syarikat')) {
                $table->string('nama_syarikat')->nullable();
            }

            if (!Schema::hasColumn('borang_aduan_kerosakan', 'no_tel_syarikat')) {
                $table->string('no_tel_syarikat')->nullable();
            }

            if (!Schema::hasColumn('borang_aduan_kerosakan', 'tarikh_tindakan')) {
                $table->date('tarikh_tindakan')->nullable();
            }

            if (!Schema::hasColumn('borang_aduan_kerosakan', 'tarikh_selesai')) {
                $table->date('tarikh_selesai')->nullable();
            }

            if (!Schema::hasColumn('borang_aduan_kerosakan', 'catatan_pembekal')) {
                $table->text('catatan_pembekal')->nullable();
            }

        });
    }

    public function down()
    {
        Schema::table('borang_aduan_kerosakan', function (Blueprint $table) {
            $table->dropColumn([
                'remarks',
                'nama_syarikat',
                'no_tel_syarikat',
                'tarikh_tindakan',
                'tarikh_selesai',
                'catatan_pembekal'
            ]);
        });
    }
};
