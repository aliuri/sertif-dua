<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddParticipantmarginToSertifsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sertifs', function (Blueprint $table) {
            $table->bigInteger('peserta_top')->nullable();
            $table->bigInteger('peserta_left')->nullable();
            $table->bigInteger('peserta_right')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sertifs', function (Blueprint $table) {
            //
        });
    }
}
