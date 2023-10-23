<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrestasiDoktersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prestasi_dokters', function (Blueprint $table) {
            $table->id();
            $table->string('kd_dokter');
            $table->string('prestasi');
            $table->date('tahun')->nullable();
            $table->timestamps();
            $table->foreign('kd_dokter')->references('kd_dokter')->on('dokter')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // remove foreign key
        Schema::table('prestasi_dokters', function (Blueprint $table) {
            $table->dropForeign('kd_dokter');
        });
        Schema::dropIfExists('prestasi_dokters');
    }
}