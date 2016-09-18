<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHomepageDisplayCheckboxToVenue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('venues', function (Blueprint $table) {
            $table->boolean('show_on_homepage');
            $table->string('venue_logo_url');
            $table->string('venue_logo_key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('venues', function (Blueprint $table) {
            $table->dropColumn('show_on_homepage');
            $table->dropColumn('venue_logo_url');
            $table->dropColumn('venue_logo_key');
        });
    }
}
