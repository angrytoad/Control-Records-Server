<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameBandsAdditionalColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bands_additional', function (Blueprint $table) {
            $table->renameColumn('band_banner_id','band_banner_url');
            $table->renameColumn('band_avatar_id','band_avatar_ur');
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
            $table->renameColumn('band_banner_url','band_banner_id');
            $table->renameColumn('band_avatar_url','band_avatar_id');
        });
    }
}
