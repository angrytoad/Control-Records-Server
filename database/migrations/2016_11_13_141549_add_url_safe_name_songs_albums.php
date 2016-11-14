<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUrlSafeNameSongsAlbums extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('songs', function (Blueprint $table) {
            $table->string('url_safe_name');
        });

        Schema::table('albums', function (Blueprint $table) {
            $table->string('url_safe_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('songs', function (Blueprint $table) {
            $table->dropColumn('url_safe_name');
        });

        Schema::table('albums', function (Blueprint $table) {
            $table->dropColumn('url_safe_name');
        });
    }
}
