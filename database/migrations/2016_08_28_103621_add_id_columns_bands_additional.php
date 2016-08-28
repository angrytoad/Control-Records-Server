<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdColumnsBandsAdditional extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bands_additional', function (Blueprint $table) {
            $table->string('band_banner_key');
            $table->string('band_avatar_key');
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
            $table->dropColumn('band_banner_key');
            $table->dropColumn('band_avatar_key');
        });
    }
}
