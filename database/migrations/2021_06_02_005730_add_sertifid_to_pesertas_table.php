<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSertifidToPesertasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pesertas', function (Blueprint $table) {
            $table->unsignedBigInteger('sertif_id');
            $table->foreign('sertif_id')->references('id')->on('sertifs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pesertas', function (Blueprint $table) {
            //
        });
    }
}
