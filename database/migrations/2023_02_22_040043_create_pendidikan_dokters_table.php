<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePendidikanDoktersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pendidikan_dokters', function (Blueprint $table) {
            $table->id();
            $table->string('kd_dokter');
            $table->string('pendidikan');
            $table->string('perguruan_tinggi')->nullable();
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
        Schema::table('pendidikan_dokters', function (Blueprint $table) {
            $table->dropForeign(['kd_dokter']);
        });
        Schema::dropIfExists('pendidikan_dokters');
    }
}