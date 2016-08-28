<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameBandsAdditionalColumns2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bands_additional', function (Blueprint $table) {
            $table->renameColumn('band_avatar_ur','band_avatar_url');
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
            $table->renameColumn('band_avatar_url','band_avatar_ur');
        });
    }
}
