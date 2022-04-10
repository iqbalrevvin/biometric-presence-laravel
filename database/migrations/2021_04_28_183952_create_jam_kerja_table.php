<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJamKerjaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jam_kerja', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->time('jam_masuk_mulai',0);
            $table->time('jam_masuk_selesai',0);
            $table->time('jam_pulang_mulai',0);
            $table->time('jam_pulang_selesai',0);
            $table->string('keterangan')->nullable();
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
        Schema::dropIfExists('jam_kerja');
    }
}
