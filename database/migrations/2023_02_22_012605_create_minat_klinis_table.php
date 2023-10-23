<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMinatKlinisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('minat_klinis', function (Blueprint $table) {
            $table->id();
            $table->string('kd_dokter');
            $table->string('minat');
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
        // remove foreign key constraint
        Schema::table('minat_klinis', function (Blueprint $table) {
            $table->dropForeign(['kd_dokter']);
        });
        Schema::dropIfExists('minat_klinis');
    }
}