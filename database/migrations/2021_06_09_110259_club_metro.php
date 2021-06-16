<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ClubMetro extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('club-metro', function (Blueprint $table) {
            $table->bigInteger('club_id')->unsigned();
            $table->bigInteger('metro_id')->unsigned();
            $table->foreign('club_id')->references('id')->on('clubs');
            $table->foreign('metro_id')->references('id')->on('metros');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('club_metro');
    }
}
