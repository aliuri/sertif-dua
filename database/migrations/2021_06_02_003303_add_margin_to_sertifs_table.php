<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMarginToSertifsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sertifs', function (Blueprint $table) {
            $table->bigInteger('margin_top')->nullable();
            $table->bigInteger('margin_left')->nullable();
            $table->bigInteger('margin_right')->nullable();
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
