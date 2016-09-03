<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BandAddAboutColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bands_additional', function (Blueprint $table) {
            $table->longText('about');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bands_additional', function (Blueprint $table) {
            $table->dropColumn('about');
        });
    }
}
