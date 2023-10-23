<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengaduansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengaduans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_laporan_id')->constrained('jenis_laporan_pengaduans')->onDelete('cascade');
            $table->string('nama')->nullable();
            $table->string('whatsapp')->nullable();
            $table->date('tanggal_kejadian')->nullable();
            $table->string('lokasi_kejadian')->nullable();
            $table->text('pengaduan');
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pengaduans');
    }
}