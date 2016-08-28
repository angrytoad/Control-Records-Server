<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdditionalBandDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bands_additional', function (Blueprint $table) {
            $table->increments('id');
            $table->string('band_id')->unique();
            $table->string('band_banner_id');
            $table->string('band_avatar_id');
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
        Schema::drop('bands_additional');
    }
}
